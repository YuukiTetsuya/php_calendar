<?php

$tail = '';
$lastDayOfPrevMonth = new DateTime('last day of previous month');
while($lastDayOfPrevMonth->format('w') < 6){
  $tail = sprintf('<td class="gray">%d</td>', $lastDayOfPrevMonth->format('d')) .
  $tail;
  $lastDayOfPrevMonth->sub(new DateInterval('P1D'));
}

$body = '';
// 特定の期間の日付オブジェクトを作る
$period = new DatePeriod(
// 1日から末日までの日付を作る
  new DateTime('first day of this month'),
  new DateInterval('P1D'),
  new DateTime('first day of next month')
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
$firstDayOfNextMonth = new DateTime('first day of next month');
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
           <th><a href="">&laquo;</a></th>
           <th colspan="5">August 2019</a></th>
           <th><a href="">&raquo;</a></th>
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
           <th colspan="7"><a href="">Today</a></th>
         </tr>
       </tfoot>
     </table>
   </body>
 </html>
