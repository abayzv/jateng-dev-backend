<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Brands
        $logitech = Brand::create([
            "name" => "Logitech",
            "slug" => "logitech",
            "image" => "https://images.tokopedia.net/img/cache/215-square/shops-1/2017/11/22/2670965/2670965_ecbe1b84-24f0-48dd-b450-16ef8f657533.jpg",
        ]);

        $lenovo = Brand::create([
            "name" => "Lenovo",
            "slug" => "lenovo",
            "image" => "https://images.tokopedia.net/img/cache/215-square/GAnVPX/2021/6/2/43c32bda-60e1-4ee9-844a-afb5faee2806.jpg"
        ]);

        $rexus = Brand::create([
            "name" => "Rexus",
            "slug" => "rexus",
            "image" => "https://images.tokopedia.net/img/cache/215-square/GAnVPX/2023/1/18/60fa3db7-223f-425e-a923-561054d25bde.jpg"
        ]);
        // End Create Brands

        // Create Categories
        $parentCategory = Category::create([
            "name" => "Computer Accessories",
            "slug" => "computer-accessories",
            "parent_id" => null,
        ]);

        $categories1 = Category::create([
            "name" => "Mouse",
            "slug" => "mouse",
            "parent_id" => $parentCategory->id,
        ]);

        $categories2 = Category::create([
            "name" => "Keyboard",
            "slug" => "keyboard",
            "parent_id" => $parentCategory->id,
        ]);

        $categories3 = Category::create([
            "name" => "Headset",
            "slug" => "headset",
            "parent_id" => $parentCategory->id,
        ]);
        // End Create Categories

        // Upload Image
        $imageUrl = [
            "https://images.tokopedia.net/img/cache/900/VqbcmM/2023/10/24/b4523843-8289-4f39-a253-3bad8fc7ca67.jpg",
            "https://images.tokopedia.net/img/cache/900/VqbcmM/2023/10/24/63c2732e-3d3e-442b-9005-64323e7951ce.jpg",
            "https://images.tokopedia.net/img/cache/900/VqbcmM/2023/7/20/1c169bd2-201b-4767-941c-a11e3dd97916.jpg",
        ];
        $image = [];
        foreach ($imageUrl as $value) {
            $url = uploadImageFromUrl($value);
            array_push($image, $url);
        }
        // End Upload Image

        $product1 = Product::create([
            "name" => "Logitech G923 True Force Wheel + Logitech Driving Shifter Bundling",
            "description" => "Logitech G923 True Force Wheel + Logitech Driving Shifter Bundling",
            "price" => 6248000,
            "brand_id" => $logitech->id,
            "category_id" => $categories1->id,
            "user_id" => 1,
            "images" => [$image[0]],
        ]);

        $product2 = Product::create([
            "name" => "Logitech G PRO X SUPERLIGHT 2 Mouse Gaming Wireless E-Sports - Magenta",
            "description" => "Logitech G PRO X SUPERLIGHT 2 Mouse Gaming Wireless E-Sports - Magenta",
            "price" => 500000,
            "brand_id" => $logitech->id,
            "category_id" => $categories1->id,
            "user_id"  => 1,
            "images" => [$image[1]],
        ]);

        $product3 = Product::create([
            "name" => "Logitech G502 Hero Gaming Mouse",
            "description" => "Mouse Gaming Logitech G502 Hero RGB 16000 DPI",
            "price" => 700000,
            "brand_id" => $logitech->id,
            "category_id" => $categories1->id,
            "user_id"  => 1,
            "images" => [$image[2]],
        ]);
    }
}
