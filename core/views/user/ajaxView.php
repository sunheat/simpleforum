<?php
/**
 * @link http://simpleforum.org/
 * @copyright Copyright (c) 2015 Simple Forum
 * @author Jiandong Yu admin@simpleforum.org
 */

use yii\helpers\Html;
use app\models\Favorite;
use app\components\SfHtml;

$this->title = Html::encode($user['username']);
$settings = Yii::$app->params['settings'];

$fomatter = Yii::$app->getFormatter();
$isGuest = Yii::$app->getUser()->getIsGuest();
if (!$isGuest) {
    $me = Yii::$app->getUser()->getIdentity();
}

$userOp = [];
if (!$isGuest && $me->isActive() && $me->id != $user['id']) {
    if (Favorite::checkFollow($me->id, Favorite::TYPE_USER, $user['id'])) {
        $favorIcon = 'fa-star';
        $favorName = '取消关注';
        $favorParams = 'unfavorite';
    } else {
        $favorIcon = 'fa-star-o';
        $favorName = '关注 Ta';
        $favorParams = 'favorite';
    }
    $userOp['follow'] = Html::a('<i class="fa '.$favorIcon.' fa-lg aria-hidden="true""></i><span class="favorite-name">'.$favorName.'</span>', null, ['class'=>'btn btn-xs btn-default favorite', 'title'=>$favorName, 'href' => 'javascript:void(0);', 'params'=> $favorParams.' user '. $user['id']]);
    $userOp['sms'] = Html::a('<i class="fa fa-envelope-o fa-lg" aria-hidden="true"></i><span class="favorite-name">私信 Ta</span>', ['service/sms', 'to'=>Html::encode($user['username'])], ['class'=>'btn btn-xs btn-default']);
}

?>

<ul class="list-group user-card">
  <li class="list-group-item text-center user-card-heading" style="background-image:url(<?php echo Yii::getAlias('@web/'.str_replace('{size}', 'cover_m', $user['userInfo']['cover']));?>)">
    <?php echo SfHtml::uImg($user, 'normal'); ?>
    <div style="margin-top:2px;">
<?php
    echo '<span class="username">', $this->title, '</span><span class="group small">', SfHtml::uGroup($user['score']);
    if ( !empty($user['userInfo']['website']) ) {
        echo ' ', Html::a('<i class="fa fa-home fa-lg" aria-hidden="true"></i>', $user['userInfo']['website'], ['target'=>'_blank']);
    }
?>
    </div>
    <div class="small time">
<?php
    echo 'from ', Yii::$app->getFormatter()->asDateTime($user['created_at'], 'y-MM-dd');
?>
    </div>
    <?php if( !empty($user['userInfo']['about']) ) : ?>
    <div class="small profile">
    <?php 
        echo '简介：'. Html::encode(mb_strlen($user['userInfo']['about'])>19?mb_substr($user['userInfo']['about'], 0, 16).'...':$user['userInfo']['about']);
    ?>
    </div>
    <?php endif ?>
  </li>
  <li class="list-group-item text-center small">
      <p><strong>关注 <?php echo $user['userInfo']['favorite_user_count'] ?></strong> | 
      <strong>粉丝 <span class="favorite-num"><?php echo $user['userInfo']['favorite_count']; ?></span></strong> |
      <strong>主题 <?php echo $user['userInfo']['topic_count']; ?></strong> |
      <strong>回复 <?php echo $user['userInfo']['comment_count']; ?></strong></p>
      <?php echo implode(' ', $userOp); ?>
  </li>
</ul>
