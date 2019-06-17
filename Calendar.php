<?php

namespace MyApp;

class Calendar{
  public $prev;
  public $next;
  public $yearMonth;
  private $_thisMonth;

  public function __construct(){

    // 現在の年/月を$tに代入し、DateTimeオブジェクトを$thisMonthに代入し、月/年をF/Yでformatメソッドで取得し、$yearMonthに代入

    try {
      if(!isset($_GET['t']) || !preg_match('/\A\d{4}-\d{2}\z/', $_GET['t'])){
        throw new \Exception();
      }
      $this->_thisMonth = new \DateTime($_GET['t']);
    } catch (\Exception $e) {
      $this->_thisMonth = new \DateTime('first day of this month');
    }

    $this->prev = $this->_createPrevLink();
    $this->next = $this->_createNextLink();
    $this->yearMonth = $this->_thisMonth->format('F Y');
  }

  private function _createPrevLink(){
    $dt = clone $this->_thisMonth;
    return $dt->modify('-1 month')->format('Y-m');
  }

  private function _createNextLink(){
    $dt = clone $this->_thisMonth;
    return $dt->modify('+1 month')->format('Y-m');
  }

  public function show(){
    $tail = $this->_getTail();
    $body = $this->_getBody();
    $head = $this->_getHead();
    $html = '<tr>' . $tail. $body. $head . '</tr>';
    echo $html;
  }

  private function _getTail(){
    $tail = '';
    $lastDayOfPrevMonth = new \DateTime('last day of '. $this->yearMonth . '-1 month');
    while($lastDayOfPrevMonth->format('w') < 6){
      $tail = sprintf('<td class="gray">%d</td>', $lastDayOfPrevMonth->format('d')) .
      $tail;
      $lastDayOfPrevMonth->sub(new \DateInterval('P1D'));
    }
    return $tail;
  }

  private function _getBody(){
    $body = '';
    // 特定の期間の日付オブジェクトを作る
    $period = new \DatePeriod(
    // 1日から末日までの日付を作る
      new \DateTime('first day of '. $this->yearMonth),
      new \DateInterval('P1D'),
      new \DateTime('first day of '. $this->yearMonth . ' +1 month')
    );

    $today = new \DateTime('today');

    // body変数に連結し、書式付きで文字列を作るsprintfで、tdタグの中にd要素(1〜末日)を入れる
    // format(w)で0(日曜)~6(土曜)まで入れ、7で割ったあまりが0の場合(日曜の場合)は、trタグを追加で入れる
    foreach ($period as $day) {
      if ($day->format('w') === '0'){
        $body .= '</tr><tr>';
      };
      $body .= sprintf('<td class="youbi_%d">%d</td>', $day->format('w'),
      $day->format('d'));
    }
  return $body;
  }

  private function _getHead(){
    // DateTimeオブジェクトで、0(日曜)~6(土曜)が、日曜り大きい月曜〜土曜であれば、

    $head = '';
    $firstDayOfNextMonth = new \DateTime('first day of '. $this->yearMonth . ' +1 month');
    while($firstDayOfNextMonth->format('w') > 0){
      $head .= sprintf('<td class="gray">%d</td>', $firstDayOfNextMonth->format('d'));
      $firstDayOfNextMonth->add(new \DateInterval('P1D'));
    }
    return $head;
  }
}

?>
