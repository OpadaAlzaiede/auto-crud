<?php

use ObadaAz\AutoCrud\Services\FileHandler;
use Illuminate\Support\Facades\File;

it('reads and replaces stub content', function () {

    File::shouldReceive('get')
        ->once()
        ->andReturn('{{model}}Controller');

    $handler = new FileHandler();
    $content = $handler->getStubContent('controller.stub', ['{{model}}' => 'User']);

    expect($content)->toBe('UserController');
});

it('handles empty replacements in getStubContent', function () {

    File::shouldReceive('get')
        ->once()
        ->andReturn('{{model}}Controller');

    $handler = new FileHandler();
    $content = $handler->getStubContent('controller.stub', []);

    expect($content)->toBe('{{model}}Controller');
});

it('throws an exception if stub file does not exist', function () {

    File::shouldReceive('get')
        ->once()
        ->andThrow(\Illuminate\Contracts\Filesystem\FileNotFoundException::class);

    $handler = new FileHandler();

    expect(fn() => $handler->getStubContent('nonexistent.stub', []))
        ->toThrow(\Illuminate\Contracts\Filesystem\FileNotFoundException::class);
});

it('creates a file and its directory', function () {

    File::shouldReceive('ensureDirectoryExists')->once()->with('/path/to');
    File::shouldReceive('put')->once()->with('/path/to/file', 'content');

    $handler = new FileHandler();
    $handler->createFile('/path/to/file', 'content');
});

it('overwrites an existing file', function () {

    File::shouldReceive('ensureDirectoryExists')->once();
    File::shouldReceive('put')->once()->with('/path/to/file', 'new content');

    $handler = new FileHandler();
    $handler->createFile('/path/to/file', 'new content');
});

it('throws an exception if directory cannot be created', function () {

    File::shouldReceive('ensureDirectoryExists')
        ->once()
        ->andThrow(\RuntimeException::class);

    $handler = new FileHandler();

    expect(fn() => $handler->createFile('/path/to/file', 'content'))
        ->toThrow(\RuntimeException::class);
});

it('appends content to an existing file', function () {

    File::shouldReceive('append')
        ->once()
        ->with('/path/to/file', "new content\n");

    $handler = new FileHandler();
    $handler->appendToFile('/path/to/file', 'new content');
});


it('creates a file if it does not exist', function () {

    File::shouldReceive('append')
        ->once()
        ->with('/path/to/file', "new content\n");

    $handler = new FileHandler();
    $handler->appendToFile('/path/to/file', 'new content');
});

it('throws an exception if file cannot be appended to', function () {

    File::shouldReceive('append')
        ->once()
        ->andThrow(\RuntimeException::class);

    $handler = new FileHandler();

    expect(fn() => $handler->appendToFile('/path/to/file', 'content'))
        ->toThrow(\RuntimeException::class);
});


it('handles empty content in createFile', function () {

    File::shouldReceive('ensureDirectoryExists')->once();
    File::shouldReceive('put')->once()->with('/path/to/file', '');

    $handler = new FileHandler();
    $handler->createFile('/path/to/file', '');
});

it('handles empty content in appendToFile', function () {

    File::shouldReceive('append')
        ->once()
        ->with('/path/to/file', "\n");

    $handler = new FileHandler();
    $handler->appendToFile('/path/to/file', '');
});