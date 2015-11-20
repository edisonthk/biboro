<?php

use Faker\Provider\Base;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
$factory->define(Biboro\Model\Account::class, function ($faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'lang' => '',
        'locate' => '',
    ];
});

$factory->define(Biboro\Model\Snippet::class, function ($faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->sentence,
        'lang' => 'ja',
        'deleted_at' => null,
    ];
});

$factory->define(Biboro\Model\Tag::class, function ($faker) {
    return [
        'name' => Base::randomElements(["server","AngularJS-watch","range-base_for","JavaScript-canvas","Chrome-Biboro","シリアル通信","React.js","タイマー","端末","Terminal","画像処理","AngularJS-http","express.js","node.js","AngularJS-resources","Android-ListView","Terminal","CSRF","XSRF","JSON"]),
    ];
});