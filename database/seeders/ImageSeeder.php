<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Image::insert([
            [
                'linked_type' => 'route',
                'linked_id'   => 1,
                'url'         => 'storage/images/vungtau.jpg',
                'alt_text'    => 'Vũng Tàu',
                'sort_order'  => 1,
            ],
            [
                'linked_type' => 'route',
                'linked_id'   => 2,
                'url'         => 'storage/images/dalat.jpg',
                'alt_text'    => 'Đà Lạt',
                'sort_order'  => 2,
            ],
            [
                'linked_type' => 'route',
                'linked_id'   => 3,
                'url'         => 'storage/images/nhatrang.jpg',
                'alt_text'    => 'Nha Trang',
                'sort_order'  => 3,
            ],
            [
                'linked_type' => 'route',
                'linked_id'   => 4,
                'url'         => 'storage/images/hanoi.jpg',
                'alt_text'    => 'Hà Nội',
                'sort_order'  => 4,
            ],
            [
                'linked_type' => 'route',
                'linked_id'   => 5,
                'url'         => 'storage/images/haiphong.jpg',
                'alt_text'    => 'Hải Phòng',
                'sort_order'  => 5,
            ],
            [
                'linked_type' => 'route',
                'linked_id'   => 6,
                'url'         => 'storage/images/sapa.jpg',
                'alt_text'    => 'Sa Pa',
                'sort_order'  => 6,
            ],
            [
                'linked_type' => 'route',
                'linked_id'   => 7,
                'url'         => 'storage/images/hue.jpg',
                'alt_text'    => 'Huế',
                'sort_order'  => 7,
            ],
            [
                'linked_type' => 'route',
                'linked_id'   => 8,
                'url'         => 'storage/images/quangninh.jpg',
                'alt_text'    => 'Quảng Ninh',
                'sort_order'  => 8,
            ],
            [
                'linked_type' => 'operator',
                'linked_id'   => 1,
                'url'         => '/images/operators/futa.png',
                'alt_text'    => 'Futa Bus Lines',
                'sort_order'  => 1,
            ],
            [
                'linked_type' => 'operator',
                'linked_id'   => 2,
                'url'         => '/images/operators/saonghe.png',
                'alt_text'    => 'Sao Nghệ Limousine',
                'sort_order'  => 2,
            ],
            [
                'linked_type' => 'operator',
                'linked_id'   => 3,
                'url'         => '/images/operators/hoanglong.png',
                'alt_text'    => 'Hoàng Long Bus',
                'sort_order'  => 3,
            ],
            [
                'linked_type' => 'operator',
                'linked_id'   => 4,
                'url'         => '/images/operators/vanminh.png',
                'alt_text'    => 'Văn Minh Bus',
                'sort_order'  => 4,
            ],
            [
                'linked_type' => 'operator',
                'linked_id'   => 5,
                'url'         => '/images/operators/thanhbuoi.png',
                'alt_text'    => 'Thành Bưởi',
                'sort_order'  => 5,
            ],
            [
                'linked_type' => 'operator',
                'linked_id'   => 6,
                'url'         => '/images/operators/hungthanh.png',
                'alt_text'    => 'Hưng Thành',
                'sort_order'  => 6,
            ],
            [
                'linked_type' => 'operator',
                'linked_id'   => 7,
                'url'         => '/images/operators/limousineviet.png',
                'alt_text'    => 'Limousine Việt',
                'sort_order'  => 7,
            ],
            [
                'linked_type' => 'vehicle_type',
                'linked_id'   => 1,
                'url'         => '/images/vehicles/limousine-22.png',
                'alt_text'    => 'Limousine 22 giường',
                'sort_order'  => 1,
            ],
            [
                'linked_type' => 'vehicle_type',
                'linked_id'   => 2,
                'url'         => '/images/vehicles/sleeper-40.png',
                'alt_text'    => 'Xe giường nằm 40 chỗ',
                'sort_order'  => 2,
            ],
            [
                'linked_type' => 'vehicle_type',
                'linked_id'   => 3,
                'url'         => '/images/vehicles/seat-45.png',
                'alt_text'    => 'Xe ghế ngồi 45 chỗ',
                'sort_order'  => 3,
            ],
            [
                'linked_type' => 'vehicle_type',
                'linked_id'   => 4,
                'url'         => '/images/vehicles/vip-16.png',
                'alt_text'    => 'Limousine VIP 16 chỗ',
                'sort_order'  => 4,
            ],
            [
                'linked_type' => 'vehicle_type',
                'linked_id'   => 5,
                'url'         => '/images/vehicles/express-29.png',
                'alt_text'    => 'Xe ghế ngồi 29 chỗ',
                'sort_order'  => 5,
            ],
            [
                'linked_type' => 'vehicle_type',
                'linked_id'   => 6,
                'url'         => '/images/vehicles/dormitory-34.png',
                'alt_text'    => 'Xe giường tầng 34 chỗ',
                'sort_order'  => 6,
            ],
            [
                'linked_type' => 'vehicle_type',
                'linked_id'   => 7,
                'url'         => '/images/vehicles/vip-cabin.png',
                'alt_text'    => 'Cabin VIP',
                'sort_order'  => 7,
            ],
            [
                'linked_type' => 'schedule',
                'linked_id'   => 1,
                'url'         => '/images/schedules/trip1.jpg',
                'alt_text'    => 'Chuyến 01: Sài Gòn - Vũng Tàu',
                'sort_order'  => 1,
            ],
            [
                'linked_type' => 'schedule',
                'linked_id'   => 2,
                'url'         => '/images/schedules/trip2.jpg',
                'alt_text'    => 'Chuyến 02: Sài Gòn - Đà Lạt',
                'sort_order'  => 2,
            ],
            [
                'linked_type' => 'schedule',
                'linked_id'   => 3,
                'url'         => '/images/schedules/trip3.jpg',
                'alt_text'    => 'Chuyến 03: Hà Nội - Sa Pa',
                'sort_order'  => 3,
            ],
            [
                'linked_type' => 'schedule',
                'linked_id'   => 4,
                'url'         => '/images/schedules/trip4.jpg',
                'alt_text'    => 'Chuyến 04: Đà Nẵng - Huế',
                'sort_order'  => 4,
            ],
            [
                'linked_type' => 'schedule',
                'linked_id'   => 5,
                'url'         => '/images/schedules/trip5.jpg',
                'alt_text'    => 'Chuyến 05: Hà Nội - Hải Phòng',
                'sort_order'  => 5,
            ],
            [
                'linked_type' => 'amenity',
                'linked_id'   => 1,
                'url'         => '/images/amenities/wifi.png',
                'alt_text'    => 'Wifi miễn phí',
                'sort_order'  => 1,
            ],
            [
                'linked_type' => 'amenity',
                'linked_id'   => 2,
                'url'         => '/images/amenities/water.png',
                'alt_text'    => 'Nước uống miễn phí',
                'sort_order'  => 2,
            ],
            [
                'linked_type' => 'amenity',
                'linked_id'   => 3,
                'url'         => '/images/amenities/blanket.png',
                'alt_text'    => 'Chăn đắp',
                'sort_order'  => 3,
            ],
            [
                'linked_type' => 'amenity',
                'linked_id'   => 4,
                'url'         => '/images/amenities/charging.png',
                'alt_text'    => 'Ổ cắm sạc',
                'sort_order'  => 4,
            ],
            [
                'linked_type' => 'amenity',
                'linked_id'   => 5,
                'url'         => '/images/amenities/toilet.png',
                'alt_text'    => 'Nhà vệ sinh trên xe',
                'sort_order'  => 5,
            ],
            [
                'linked_type' => 'amenity',
                'linked_id'   => 6,
                'url'         => '/images/amenities/tv.png',
                'alt_text'    => 'Tivi màn hình lớn',
                'sort_order'  => 6,
            ],
            [
                'linked_type' => 'amenity',
                'linked_id'   => 7,
                'url'         => '/images/amenities/ac.png',
                'alt_text'    => 'Điều hòa',
                'sort_order'  => 7,
            ],
        ]);
    }
}
