<?php

require 'Calendar.php';

function h($s){
  return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

$cal = new \MyApp\Calendar();

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
           <th><a href="/?t=<?php echo h($cal->prev) ?>">&laquo;</a></th>
           <th colspan="5"><?php echo h($cal->yearMonth); ?></a></th>
           <th><a href="/?t=<?php echo h($cal->next) ?>">&raquo;</a></th>
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
         <?php echo $cal->show(); ?>
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
