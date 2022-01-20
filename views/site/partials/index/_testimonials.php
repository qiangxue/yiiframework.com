<?php
$this->registerJs("
    $('#Glide2').glide({
        type: 'carousel',
        autoheight: true,
        autoplay: 10000,
    });
    var slideButton = $('.glide__bullet');
    slideButton.attr('title', 'pagination');
");
?>

<div class="testimonials">
    <div class="dashed-heading-front-section">
        <span>Testimonials</span>
    </div>
    <div class="row testimonial">

        <div id="Glide2" class="glide">
            <div class="glide__wrapper">
                <ul class="glide__track">
                    <?php foreach($testimonials as $testimonial): ?>
                        <li class="glide__slide">
                            <div class="testimonial-content">
                                <img src="<?= Yii::getAlias($testimonial['image']) ?>" alt="" class="img-responsive"/>
                                <div class="title">
                                    <a href="<?= $testimonial['url'] ?>"><?= $testimonial['title'] ?></a>
                                </div>
                                <div class="description">
                                    <?= $testimonial['description'] ?>
                                </div>
                                <div class="quote">
                                    <?= $testimonial['quote'] ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            </div>
            <div class="glide__bullets hidden-xs"></div>
        </div>
    </div>
</div>
