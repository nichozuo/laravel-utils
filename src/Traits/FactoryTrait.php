<?php


namespace Nichozuo\LaravelUtils\Traits;


use Faker\Generator;
use Ramsey\Uuid\Uuid;

trait FactoryTrait
{
    public function getImageJson(Generator $faker, $width, $height, $category, $word = 'Faker'): array
    {
        // {"uid": "rc-upload-1600503228542-5", "url": "http://localhost:8000/storage/upload/202009//ygDTHx2TFjnRtnzQjtWIHfq3KF5k6en7yoHFP5xj.jpeg", "name": "下载.jpeg", "status": "done"}
        return [
            [
                'uid' => Uuid::uuid1(),
                'url' => $faker->imageUrl($width, $height, $category, true, $word),
                'name' => $faker->unique()->name,
                'status' => 'done'
            ]
        ];
    }

    public function getImagesJson(Generator $faker, $width, $height, $count, $category, $word = 'Faker'): array
    {
        // {"uid": "rc-upload-1600503228542-5", "url": "http://localhost:8000/storage/upload/202009//ygDTHx2TFjnRtnzQjtWIHfq3KF5k6en7yoHFP5xj.jpeg", "name": "下载.jpeg", "status": "done"}
        $arr = [];
        for ($i = 0; $i < $count; $i++) {
            $arr[] = [
                'uid' => Uuid::uuid1(),
                'url' => $faker->unique()->imageUrl($width, $height, $category, true, $word),
                'name' => $faker->unique()->name,
                'status' => 'done'
            ];
        }
        return $arr;
    }
}
