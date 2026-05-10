<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Artists
        Schema::create('DS_Artists', function (Blueprint $table) {
            $table->id('artist_id');
            $table->string('name');
            $table->integer('year_born')->nullable();
            $table->integer('year_death')->nullable();
            $table->text('bio')->nullable();
            $table->string('img_path')->nullable();
            $table->timestamps();
        });

        // 2. Products (depends on artists)
        Schema::create('DS_Products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('title');
            $table->integer('year')->nullable();
            $table->string('genre')->nullable();
            $table->string('category')->default('artwork');
            $table->integer('price')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreignId('artist_id')->nullable()->references('artist_id')->on('DS_Artists')->cascadeOnDelete();
        });

        // 3. Orders (depends on users)
        Schema::create('DS_Orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedInteger('total')->default(0);
            $table->string('payment_method')->nullable();

            // Shipping snapshot
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('DS_Users')->nullOnDelete();
            $table->string('view_token')->nullable()->unique();
        });


        // 4. Order items (depends on orders + products)
        Schema::create('DS_OrderItems', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            // Price/title/artist snapshot at time of purchase
            $table->string('title')->nullable();
            $table->string('artist')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('DS_Orders')->cascadeOnDelete();
            $table->foreign('product_id')->references('product_id')->on('DS_Products')->cascadeOnDelete();
        });

        // 5. Cart items (depends on users + products)
        Schema::create('DS_CartItems', function (Blueprint $table) {
            $table->id('cart_item_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('DS_Users')->cascadeOnDelete();
            $table->foreign('product_id')->references('product_id')->on('DS_Products')->cascadeOnDelete();
        });

        // 6. Save items (depends on users + products)
        Schema::create('DS_SaveItems', function (Blueprint $table) {
            $table->id('save_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('DS_Users')->cascadeOnDelete();
            $table->foreign('product_id')->references('product_id')->on('DS_Products')->cascadeOnDelete();
        });

        // 7. Product images (depends on products)
        Schema::create('DS_ProductImages', function (Blueprint $table) {
            $table->id('image_id');
            $table->unsignedBigInteger('product_id');
            $table->string('img_path');
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('product_id')
                ->references('product_id')
                ->on('DS_Products')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('DS_ProductImages');
        Schema::dropIfExists('DS_SaveItems');
        Schema::dropIfExists('DS_CartItems');
        Schema::dropIfExists('DS_OrderItems');
        Schema::dropIfExists('DS_Orders');
        Schema::dropIfExists('DS_Products');
        Schema::dropIfExists('DS_Artists');

        Schema::enableForeignKeyConstraints();
    }
};
