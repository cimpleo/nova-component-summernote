# Nova Summernote

Nova field wrapper around the [summernote](https://github.com/summernote/summernote).

## Installation
1. `composer require cimpleo/nova-summernote`
2. Run `php artisan vendor:publish --tag=public` to puslish the fonts to the public directory
2. That's it.

## Usage
Add the following to one of your resources

Import NovaSummernote

`use Cimpleo\Summernote\Summernote;`

Then call it inside the fields method of your resource.

```
    public function fields(Request $request)
    {
        return [
            ...,
            NovaSummernote::make('body'),
            ...
        ];
    }
```