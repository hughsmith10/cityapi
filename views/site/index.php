<?php
use yii\grid\GridView;
/* @var $this yii\web\View */
$this->title = 'My Yii Application';
?>

<div class="site-index">

    <div class="well text-center">

        <h3 style="margin-top:0;">Find Utah Cities by Zip Code</h3>

        <form class="form-inline" action="/" method="GET">

                <div class="form-group">
                        <input type="text" class="form-control text-center" name="zip" value="<?=$zip;?>" placeholder="Enter a Utah Zip" />
                </div>

                <input type="submit" class="btn btn-success" value="Find Cities" />

        </form>

    </div>

    <div class="body-content">

        <?php echo GridView::widget(['dataProvider' => $dataProvider ]); ?>

    </div>

</div>

