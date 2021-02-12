# Scrapoxy PHP API Documentation

<h1 align="center">Scrapoxy Controller</h1>

<p align="center">
This package interacts with the API from scrapoxy via a straightforward interface
</p>


## Installing

```shell
$ composer require uwebpro/scrapoxy-api
```

## Usage
Initialse like so, parameter one is your scrapoxy conf.json file containing


```php=
    use UWebPro\Scrapoxy\Container;
    
    $api = new Container(__DIR__ . '/conf.json');
```

If you are using a different scrapoxy host that can be changed like so, by default it is http://127.0.0.1:8889

```php=
    use UWebPro\Scrapoxy\Container;

    $api = new Container(__DIR__ . '/conf.json', 'http://127.0.0.1:8889);
```

## Common functions
To start and stop a proxy pool

if values are left as null they will resume from previously set scaling

```php=
    $api->start(?int $min, ?int $required, ?int $max);
    $api->stop();
```

To Scale your proxy pool

```php=
    $api->rescale(int $min, int $required, int $max);
```

To list proxy instances as objects
 ```php=
     $api->getInstances();
```


### Instance object
The following properties are available on the instance object
```php=
    public string $name;
    public string $type;
    public string $status;
    public array $address;
    public string $region;
    public bool $alive;
    public string $useragent;
```

As well as the following method
```php=
    $instance->remove();
```

An alternative to removing instances is 
```php=
    $api->removeInstance($name);
```


### Scrapoxy config
Scrapoxy's config can be read and updated using the following methods
```php=
    $api->getConfig(); //returns of config array
    
    $api->updateConfig($configArray);
```

ALL METHODS RETURN API RESPONSE

## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/vendor/package/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/vendor/package/issues).
3. Contribute new features or update the wiki.

You just need to make sure that you follow PSR coding guidelines.


## License

MIT
