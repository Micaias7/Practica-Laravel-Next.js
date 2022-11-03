<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function can_get_all_books() {
        $books = Book::factory(4)->create();

        $this->getJson(route("books.index"))
            ->assertJsonFragment([
                "title" => $books[0]->title
            ])->assertJsonFragment([
                "title" => $books[1]->title
            ]);
    }

    /** @test */
    function can_get_one_book() {
        $book = Book::factory()->create();

        $response = $this->getJson(route("books.show", $book))
            ->assertJsonFragment([
                "title" => $book->title
            ]);
    }

    /** @test */
    function can_create_books() {
        $this->postJson(route("books.store"), [])
            ->assertJsonValidationErrorFor("title");

        $this->postJson(route("books.store"), [
            "title" => "My new book"
        ])->assertJsonFragment([
            "title" => "My new book"
        ]);

        $this->assertDatabaseHas("books", [
            "title" => "My new book"
        ]);
    }

    /** @test */
    function can_update_books() {
        $book = Book::factory()->create();

        $this->patchJson(route("books.update", $book), [])
            ->assertJsonValidationErrorFor("title");

        $this->patchJson(route("books.update", $book), [
            "title" => "Edited book"
        ])->assertJsonFragment([
            "title" => "Edited book"
        ]);

        $this->assertDatabaseHas("books", [
            "title" => "Edited book"
        ]);
    }

    /** @test */
    function can_delete_books() {
        $book = Book::factory()->create();

        $this->deleteJson(route("books.destroy", $book))
            ->assertNoContent();

        $this->assertDatabaseCount("books", 0);
    }
}
