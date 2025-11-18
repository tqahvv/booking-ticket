<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $content1 = '
            <p>Tháng 11 là lúc thời tiết đẹp nhất miền Bắc, rất thích hợp để du lịch khám phá. Đặc biệt, vùng núi phía Bắc như Hà Giang đang vào mùa hoa tam giác mạch rực rỡ.</p>
            <figure class="align-center">
                <img src="/assets/client/img/image-post-1.jpg" alt="Đồng Văn mùa hoa tam giác mạch" />
                <figcaption>1. Đồng Văn mùa hoa tam giác mạch</figcaption>
            </figure>
            <p>Đặc điểm giữa tháng 11 là lúc Đồng Văn chìm trong không khí lễ hội hoa tam giác mạch. Du khách có thể đến đây đặt vé xe khách trực tuyến từ Hà Nội để tận hưởng vẻ đẹp thiên nhiên hùng vĩ này.</p>
            <figure class="align-right">
                <img src="/assets/client/img/image-post-2.jpg" alt="Hình ảnh check-in" />
                <figcaption>Vẻ đẹp bất tận của núi rừng Tây Bắc.</figcaption>
            </figure>
            <p>Ngoài ra, nếu bạn muốn tìm kiếm điểm đến miền Nam, Vũng Tàu là một lựa chọn tuyệt vời với tượng Chúa giang tay và bãi biển xanh mát. Hãy thử đặt vé xe giường nằm để chuyến đi thoải mái nhất!</p>
        ';

        DB::table('posts')->insert([
            [
                'title' => 'Top 5 thiên đường du lịch tháng 11 dành cho hội mê xe dịch',
                'slug' => Str::slug('Top 5 thiên đường du lịch tháng 11 dành cho hội mê xe dịch'),
                'excerpt' => 'Tháng 11 là lúc tuyệt vời cho chuyến du lịch, chúng tôi đã tổng hợp những điểm đến hot nhất để bạn có thể đặt vé xe khách dễ dàng.',
                'content' => $content1,
                'image_url' => '/assets/client/img/post-thumbnail-1.jpg',
                'user_id' => 2,
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Cẩm nang mua vé xe khách online Traveloka siêu tiện lợi',
                'slug' => Str::slug('Cẩm nang mua vé xe khách online Traveloka siêu tiện lợi'),
                'excerpt' => 'Hướng dẫn chi tiết từng bước đặt vé xe khách trên Traveloka, đảm bảo chuyến đi suôn sẻ.',
                'content' => '<p>Mua vé xe khách mọi lúc, mọi nơi với Traveloka mà không cần tốn nhiều thời gian đến nhà ga hoặc văn phòng đại lý.</p>',
                'image_url' => '/assets/client/img/post-thumbnail-2.jpg',
                'user_id' => 2,
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
