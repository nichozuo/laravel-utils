<?php


namespace Nichozuo\LaravelUtils\Traits;


use Faker\Factory;
use Illuminate\Support\Str;

trait TestCaseTrait
{
    protected $token = '1|jhhUAcR4XLRpYUte798MOPqAuxHKShl4MgpUgJdz';
    protected $faker;
    protected $id;

    public function setUp(): void
    {
        $this->faker = Factory::create('zh_CN');
        parent::setUp();
    }

    /**
     * @param $method
     * @param array $params
     * @param array $headers
     * @return mixed
     */
    protected function go($method, array $params = [], array $headers = [])
    {
        $url = $this->getUrl($method);
        $headers['Authorization'] = 'Bearer ' . $this->token;
        $response = $this->post($url, $params, $headers);
        $json = $response->json();
        $response->assertStatus(200);
        dump(json_encode($json));
        dump($json);
    }

    /**
     * @param $number
     * @return false|string
     */
    protected function getImages($number)
    {
        $images = null;
        foreach (range(0, $number) as $index) {
            $images[] = [
                'uid' => uniqid(),
                'url' => $this->faker->imageUrl(),
                'name' => $this->faker->name,
                'loading' => false
            ];
        }
        return json_encode($images);
    }


    /**
     * @param $method
     * @return string
     */
    private function getUrl($method): string
    {
        $t1 = explode('\\', $method);
        $urls[] = 'api';
        $urls[] = Str::snake($t1[2]);
        $urls[] = str_replace(
            '::test_',
            '',
            Str::snake(
                str_replace(
                    'ControllerTest',
                    '/',
                    $t1[3]
                )
            )
        );
        return '/' . implode('/', $urls);
    }
}