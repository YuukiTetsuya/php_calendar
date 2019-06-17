<?php

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

// 現在の年/月を$tに代入し、DateTimeオブジェクトを$thisMonthに代入し、月/年をF/Yでformatメソッドで取得し、$yearMonthに代入

try {
  if(!isset($_GET['t']) || !preg_match('/\A\d{4}-\d{2}\z/', $_GET['t'])){
    throw new Exception();
  }
  $thisMonth = new DateTime($_GET['t']);
} catch (Exception $e) {
  $thisMonth = new DateTime('first day of this month');
}

$dt = clone $thisMonth;
$prev = $dt->modify('-1 month')->format('Y-m');
$dt = clone $thisMonth;
$next = $dt->modify('+1 month')->format('Y-m');

$yearMonth = $thisMonth->format('F Y');

$tail = '';
$lastDayOfPrevMonth = new DateTime('last day of '. $yearMonth . '-1 month');
while($lastDayOfPrevMonth->format('w') < 6){
  $tail = sprintf('<td class="gray">%d</td>', $lastDayOfPrevMonth->format('d')) .
  $tail;
  $lastDayOfPrevMonth->sub(new DateInterval('P1D'));
}

$body = '';
// 特定の期間の日付オブジェクトを作る
$period = new DatePeriod(
// 1日から末日までの日付を作る
  new DateTime('first day of '. $yearMonth),
  new DateInterval('P1D'),
  new DateTime('first day of '. $yearMonth . ' +1 month')
);

// body変数に連結し、書式付きで文字列を作るsprintfで、tdタグの中にd要素(1〜末日)を入れる
// format(w)で0(日曜)~6(土曜)まで入れ、7で割ったあまりが0の場合(日曜の場合)は、trタグを追加で入れる
foreach ($period as $day) {
  if ($day->format('w') % 7 === 0 ){
    $body .= '</tr><tr>';
  };
  $body .= sprintf('<td class="youbi_%d">%d</td>', $day->format('w'),
  $day->format('d'));
}

// DateTimeオブジェクトで、0(日曜)~6(土曜)が、日曜り大きい月曜〜土曜であれば、

$head = '';
$firstDayOfNextMonth = new DateTime('first day of '. $yearMonth . ' +1 month');
while($firstDayOfNextMonth->format('w') > 0){
  $head .= sprintf('<td class="gray">%d</td>', $firstDayOfNextMonth->format('d'));
  $firstDayOfNextMonth->add(new DateInterval('P1D'));
}

$html = '<tr>' . $tail. $body. $head . '</tr>';

 ?>

 <!DOCTYPE html>
 <html lang="ja">
   <head>
     <meta charset="utf-8">
     <title>PHPカレンダー</title>
     <link rel="stylesheet" href="styles.css">
   </head>
   <body>
     <table>
       <thead>
         <tr>
           <th><a href="/?t=<?php echo h($prev) ?>">&laquo;</a></th>
           <th colspan="5"><?php echo h($yearMonth); ?></a></th>
           <th><a href="/?t=<?php echo h($next) ?>">&raquo;</a></th>
         </tr>
       </thead>
       <tbody>
         <tr>
           <th>SUN</th>
           <th>MON</th>
           <th>Tue</th>
           <th>Wed</th>
           <th>Thu</th>
           <th>Fri</th>
           <th>Sat</th>
         </tr>
         <?php echo $html; ?>
         </tr>
       </tbody>
       <tfoot>
         <tr>
           <th colspan="7"><a href="/">Today</a></th>
         </tr>
       </tfoot>
     </table>
   </body>
 </html>
