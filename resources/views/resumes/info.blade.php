<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <title>農業產品產銷履歷區塊鏈資訊網</title>
    <!--Web default meta-->
    <meta name="robots" content="index, follow">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="農業產品產銷履歷區塊鏈資訊網">
    <!--Web css-->
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/animate_min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <style>
        .no_results{
            text-align: center;
        }
    </style>
</head>

<body id="page" class="resumeInfo">
    <div id="navbar_top"> <a id="rwd_nav" href="#m_nav">
            <div class="ico"><span></span></div>
        </a> </div>
    <!--上版-->
    <header id="header">
        <div class="inner">
            <div id="header_logo">
                <a href="{{route('homes.index')}}">
                    <img src="{{ asset('images/logo.svg') }}" alt="農業產品產銷履歷區塊鏈資訊網">
                </a>
            </div>
            <nav id="header_nav">
                <div id="m_nav">
                    <div id="menu">
                        <ul class="menu">
                            <li><a href="{{ route('homes.index','#about') }}" title="什麼是產銷履歷">什麼是產銷履歷</a> </li>
                            <li><a href="{{ route('homes.index','#core') }}" title="什麼是區塊鏈">什麼是區塊鏈</a> </li>
                            <li><a href="{{ route('homes.index','#banner') }}" title="如何加入產銷履歷">如何加入產銷履歷</a> </li>
                            <li><a href="{{ route('resumes.inquiry') }}" title="履歷查詢">履歷查詢</a> </li>
                            <li class="{{ $latest?'active':'' }}"><a href="{{ route('resumes.index') }}" title="最新履歷">最新履歷</a></li>
                        </ul>
                    </div>
                    <a href="javascript:$.pageslide.close()" class="bars_close"></a>
                </div>
            </nav>
        </div>
    </header>
    <div id="banner">
        <img src="{{ asset('images/page_banner.svg') }}">
        <span class="txt">{{ $latest?'最新履歷':'履歷資訊' }}</span>
    </div>
    <main id="main">
        <div class="inner">
            @if($lists->isEmpty())
            <p class="no_results">查詢無結果</p>
            @else
            @if($info)
            <section id="rsu_info">
                <div class="info_box">
                    <p class="harvesting"><span>作物批號</span><em>{{ $info->harvesting }}</em></p>
                    <p class="farm"><span>農場</span><em>{{ $info->farm }}</em></p>
                    <p class="city"><span>城市</span><em>{{ $info->city }}</em></p>
                    <p class="Township"><span>城鎮</span><em>{{ $info->Township }}</em></p>
                    <p class="address"><span>地址</span><em>{{ $info->address }}</em></p>
                    <p class="tel"><span>電話</span><em>{{ $info->tel }}</em></p>
                </div>
            </section>
            @endif
            {{-- <section id="search">
                <div class="search_box">
                    <form action="" method="get" autocomplete="off">
                        <input id="dateStar" name="dateStar" type="text" placeholder="請選擇開始日期" class="dateStyle">
                        ~
                        <input id="dateEnd" name="dateEnd" type="text" placeholder="請選擇結束日期" class="dateStyle">
                        <button type="submit" class="ico_enter"><span class="blind">搜尋</span></button>
                    </form>
                </div>
            </section> --}}
            <section id="verification">
                @foreach($lists as $l)
                <div class="vfc_box">
                    {{-- <div class="close"><span>×</span></div> --}}
                    <div class="date">{{ $l->date }}</div>
                    @if($l->validation['result'])
                    <div class="vfc_btn ok">已驗證</div>
                    @else
                    <div class="vfc_btn no">未驗證</div>
                    @endif
                    <div class="vfc_txt">
                        <p class="harvesting">作物批號:{{ $l->harvesting }}</p>
                        <p class="operators">作業場域:{{ $l->operator }}</p>
                        <p class="project">作業項目:{{ $l->task }}</p>
                        <p class="tool">工具:{{ $l->tool }}</p>
                        <p class="explain">說明:{{ $l->explain }}</p>
                    </div>
                </div>
                @endforeach
            </section>
            @endif
            <div class="back_btn"><a href="{{ route('resumes.inquiry') }}">返回</a></div>
        </div>
    </main>
    <!--下版-->
    <footer id="footer">
        <div class="inner">
            <p>行政院農委會 計畫補助</p>
            <p>國立台灣大學生物環境系統工程學系 維運</p>
            <p>106 台北市大安區羅斯福路四段1號</p>
            <p>服務電話：+886-2-33663468</p>
        </div>
    </footer>
    <!--Web jquery-->
    <script src="{{ asset('js/jquery-1.11.3.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/jquery_pageslide_min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $("#rwd_nav").pageslide({
                modal: true
            });
        });

    </script>
</body>

</html>
