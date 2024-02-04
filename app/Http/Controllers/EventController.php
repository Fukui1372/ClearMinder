<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Carbon\Carbon;

class EventController extends Controller
{
    //カレンダー表示
    public function calendar(){
        return view("calendars/calendar");
    }
    
    //新規予定追加
    public function create(Request $request, Event $event){
        //バリデーション（eventsテーブルの中でNULLを許容していないものをrequired）
        $request->validate([
            'event_name'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'event_color'=>'required',
        ]);
        
        //登録処理
        $event->name = $request->input('event_name');
        $event->description = $request->input('event_description');
        $event->started_at = date("Y-m-d", strtotime("{$request->input('start_date')}"));  
        $event->ended_at = date("Y-m-d", strtotime("{$request->input('end_date')} +1 day")); // FullCalendarが登録する終了日は仕様で1日ずれるので、その修正を行っている
        $event->event_color = $request->input('event_color');
        $event->event_border_color = $request->input('event_color');
        $event->user_id = $request->user()->id;
        $event->save();
        
        $this->saveToGC($event);
        
        //カレンダー表示画面にリダイレクトする
        return redirect(route("calendar"));
    }
    
     public function get(Request $request, Event $event) {
    //バリデーション
    $request-> validate([
            'start_date' => 'required|integer',
            'end_date' => 'required|integer'
        ]);
        
        //現在カレンダーが表示している日付の期間
        $start_date = date('Y-m-d', $request->input('start_date') / 1000); // 日付変換（JSのタイムスタンプはミリ秒なので秒に変換）
        $end_date = date('Y-m-d', $request->input('end_date') / 1000);
        
        //予定取得処理（これがaxiosのresponse.dataに入る）
        return $event-> query()
            // DBから取得する際にFullCalendarの形式にカラム名を変更する
            ->select(
                'id',
                'name as title',
                'description as description',
                'started_at as start',
                'ended_at as end',
                'event_color as backgroundColor',
                'event_border_color as borderColor'
            )
            //表示されているカレンダーのeventのみをDBから検索して表示
            ->where('ended_at', '>', $start_date)
            ->where('started_at', '<', $end_date)//AND条件
            ->get();
    }
    
     // 予定の更新
    public function update(Request $request, Event $event){
        $input = new Event();
        
        $input->name = $request->input('event_name');
        $event->description = $request->input('event_description')??'';// null なら空文字で初期化
        $input->started_at = date("Y-m-d", strtotime("{$request->input('start_date')}")); 
        $input->ended_at = date("Y-m-d", strtotime("{$request->input('end_date')} +1 day")); // FullCalendarが登録する終了日は仕様で1日ずれるので、その修正を行っている
        $input->event_color = $request->input('event_color');
        $input->event_border_color = $request->input('event_color');
        $input->user_id = $request->user()->id;
        
        // 更新する予定をDBから探し（find）、内容が変更していたらupdated_timeを変更（fill）して、DBに保存する（save）
        $event->find($request->input('id'))->fill($input->attributesToArray())->save(); // fill()の中身はArray型が必要だが、$inputのままではコレクションが返ってきてしまうため、Array型に変換
        
        // カレンダー表示画面にリダイレクトする
        return redirect(route("calendar"));
    }


    //予定の削除
    public function delete(Request $request, Event $event) {
        //削除する予定をDBから探し（find）、DBから物理削除する（delete）
        $event->find($request->input('id'))->delete();
        
        //カレンダー表示画面にリダイレクトする
        return redirect(route("calendar"));
    }
    
    private function saveToGC(Event $eventModel){
        $client = $this->getClient();
        $service = new Google_Service_Calendar($client);

        $calendarId = env('GOOGLE_CALENDAR_ID');
        $start = new Carbon($eventModel->started_at);
        $end = new Carbon($eventModel->ended_at);
        $event = new Google_Service_Calendar_Event(array(
            //タイトル
            'summary' => $eventModel->name,
            'start' => array(
                // 開始日時
                'dateTime' => $start->toAtomString(),
                'timeZone' => 'Asia/Tokyo',
            ),
            'end' => array(
                // 終了日時
                'dateTime' => $end->subDay()->toAtomString(),
                'timeZone' => 'Asia/Tokyo',
            ),
        ));

        $event = $service->events->insert($calendarId, $event);
        echo "イベントを追加しました";
    }
    
    private function getClient(){
        $client = new Google_Client();

        //アプリケーション名
        $client->setApplicationName('GoogleCalendarAPIのテスト');
        //権限の指定
        $client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        //JSONファイルの指定
        $client->setAuthConfig(storage_path('app/api-key/clearminder-55e457af4a37.json'));

        return $client;
    }
}
