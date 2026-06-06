<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('slug')->unique();
            $table->string('short_description')->nullable(); $table->longText('description')->nullable();
            $table->string('image')->nullable(); $table->string('banner_image')->nullable();
            $table->enum('type', ['uid','login'])->default('uid'); $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true); $table->unsignedInteger('sort_order')->default(0); $table->timestamps();
        });
        Schema::create('products', function (Blueprint $table) {
            $table->id(); $table->foreignId('game_id')->constrained()->cascadeOnDelete();
            $table->string('name'); $table->text('description')->nullable(); $table->unsignedBigInteger('price');
            $table->unsignedInteger('points')->default(0); $table->boolean('is_active')->default(true); $table->unsignedInteger('sort_order')->default(0); $table->timestamps();
        });
        Schema::create('banners', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->string('subtitle')->nullable(); $table->string('image')->nullable();
            $table->string('button_text')->default('Top Up Sekarang'); $table->string('url')->nullable(); $table->boolean('is_active')->default(true); $table->unsignedInteger('sort_order')->default(0); $table->timestamps();
        });
        Schema::create('popup_ads', function (Blueprint $table) {
            $table->id(); $table->string('title'); $table->string('image')->nullable(); $table->string('url')->nullable(); $table->boolean('is_active')->default(true); $table->timestamps();
        });
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id(); $table->string('name'); $table->string('code')->unique(); $table->string('category')->default('QRIS'); $table->string('logo')->nullable();
            $table->enum('fee_type',['flat','percent'])->default('flat'); $table->unsignedBigInteger('fee_value')->default(0); $table->boolean('is_active')->default(true); $table->unsignedInteger('sort_order')->default(0); $table->text('instructions')->nullable(); $table->timestamps();
        });
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); $table->string('invoice_number')->unique(); $table->string('order_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); $table->foreignId('game_id')->constrained()->cascadeOnDelete(); $table->foreignId('product_id')->constrained()->cascadeOnDelete(); $table->foreignId('payment_method_id')->constrained()->restrictOnDelete();
            $table->string('customer_name')->nullable(); $table->string('whatsapp'); $table->enum('topup_type',['uid','login']);
            $table->string('user_identifier')->nullable(); $table->string('server')->nullable(); $table->string('game_email')->nullable(); $table->string('game_password')->nullable();
            $table->unsignedBigInteger('subtotal')->default(0); $table->unsignedBigInteger('fee')->default(0); $table->unsignedBigInteger('discount')->default(0); $table->unsignedBigInteger('total')->default(0);
            $table->enum('status',['unpaid','processing','success'])->default('unpaid'); $table->string('payment_reference')->nullable(); $table->string('payment_url')->nullable(); $table->longText('qr_code')->nullable(); $table->text('notes')->nullable(); $table->timestamp('paid_at')->nullable(); $table->timestamp('completed_at')->nullable(); $table->timestamps();
        });
        Schema::create('faqs', function (Blueprint $table) {
            $table->id(); $table->string('category')->default('Umum'); $table->string('question'); $table->longText('answer'); $table->boolean('is_active')->default(true); $table->unsignedInteger('sort_order')->default(0); $table->timestamps();
        });
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id(); $table->string('ticket_number')->unique(); $table->string('topic'); $table->string('type'); $table->string('name'); $table->string('whatsapp'); $table->string('email')->nullable(); $table->longText('message'); $table->enum('status',['open','process','closed'])->default('open'); $table->text('admin_note')->nullable(); $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('support_tickets'); Schema::dropIfExists('faqs'); Schema::dropIfExists('orders'); Schema::dropIfExists('payment_methods'); Schema::dropIfExists('popup_ads'); Schema::dropIfExists('banners'); Schema::dropIfExists('products'); Schema::dropIfExists('games');
    }
};
