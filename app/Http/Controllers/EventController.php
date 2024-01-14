<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    //カレンダー表示
    public function show(){
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
        
        //カレンダー表示画面にリダイレクトする
        return redirect(route("show"));
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
}
