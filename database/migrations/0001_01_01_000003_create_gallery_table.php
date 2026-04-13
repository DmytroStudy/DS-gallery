<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Artists
        Schema::create('artists', function (Blueprint $table) {
            $table->id('artist_id');
            $table->string('name');
            $table->integer('year')->nullable();
            $table->text('description')->nullable();
            $table->string('img_path')->nullable();
            $table->timestamps();
        });

        // 2. Artworks (depends on artists)
        Schema::create('artworks', function (Blueprint $table) {
            $table->id('artwork_id');
            $table->string('title');
            $table->integer('year')->nullable();
            $table->string('genre')->nullable();
            $table->integer('price')->default(0);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();

            $table->foreignId('artist_id')->references('artist_id')->on('artists')->cascadeOnDelete();
        });

        // 3. Orders (depends on users)
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->unsignedBigInteger('user_id');
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

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // 4. Order items (depends on orders + artworks)
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('artwork_id');
            $table->integer('quantity')->default(1);
            // Price/title/artist snapshot at time of purchase
            $table->string('title')->nullable();
            $table->string('artist')->nullable();
            $table->unsignedInteger('price')->default(0);
            $table->timestamps();

            $table->foreign('order_id')->references('order_id')->on('orders')->cascadeOnDelete();
            $table->foreign('artwork_id')->references('artwork_id')->on('artworks')->cascadeOnDelete();
        });

        // 5. Cart items (depends on users + artworks)
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('cart_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('artwork_id');
            $table->integer('quantity')->default(1);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('artwork_id')->references('artwork_id')->on('artworks')->cascadeOnDelete();
        });

        // 6. Save items (depends on users + artworks)
        Schema::create('save_items', function (Blueprint $table) {
            $table->id('save_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('artwork_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('artwork_id')->references('artwork_id')->on('artworks')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('save_items');
        Schema::dropIfExists('cart_items');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('artworks');
        Schema::dropIfExists('artists');
    }
};
