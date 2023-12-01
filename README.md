# DBShenker client


## Examples

### Manipuling messages
#### Reading SCONTR message
```php
const FILE = './SCONTR.txt';

/** @var \DBShenker\Parser\DBShenkerScontrParser $t */
$t = DBShenker::parse(FILE);
//$t->setAddressGeocoder(new GoogleMapsAddressGeocoder(getenv('MAPS_KEY')));

foreach ($t as $value) {
    foreach ($value->getTasks() as $task) {
        print_r("Task ID: ".$task->getID()."\n");
        print_r("Recipient address: ".$task->getNamesAndAddresses(NameAndAddressType::RECIPIENT)[0]->getAddress()."\n");
        print_r("Number of packages: ".count($task->getPackages())."\n");
        print_r("Total weight: ".array_sum(array_map(fn(Mesurement $p) => $p->getQuantity() ,$task->getMesurements()))." kg\n");
        print_r("Comments: ".$task->getComments()."\n");
        print_r("\n\n");
    }
}
```

#### Generate REPORT message
```php

# Init filesystem, used to sync between coop and DBShenker Agency
$filesystem = new \League\Flysystem\Filesystem(
    new \League\Flysystem\Local\LocalFilesystemAdapter('/tmp')
);

# Init DBShenker options
$options = new \DBShenker\DBShenkerOptions(
    "CoopX", "362521879",
    "DBShenker Agency X", "347569895",
    $filesystem, 'coopx'
);

# Generate a sucessfull delivery with 2 POD
$reportA = (new \DBShenker\Generator\DBShenkerReport($options))
    ->setReference('AABBCC')
    ->setReceipt('123')
    ->setSituation(\DBShenker\Enum\ReportSituation::POD)
    ->setReason(\DBShenker\Enum\ReportReason::CFM)
    ->setPod(['https://foo.com/file.png', 'https://foo.com/file2.png']);

# Generate new appoitement for a failed delivery 
$reportB = (new \DBShenker\Generator\DBShenkerReport($options))
    ->setReference('ZZYYXX')
    ->setReceipt('123')
    ->setSituation(\DBShenker\Enum\ReportSituation::ENE)
    ->setReason(\DBShenker\Enum\ReportReason::NRV)
    ->setAppointment(new DateTime("05-12-2023 11:30"));
    
# Generate EDIFACT content
$message = (new \DBShenker\Generator\DBShenkerInterchange($options))
 ->addGenerator($reportA)
 ->addGenerator($reportB)
 ->generate();

echo $message;
```

### Sync with DBShenker Agency
#### Pull from DBShenker
```php
# Init filesystem, used to sync between coop and DBShenker Agency
$filesystem = new \League\Flysystem\Filesystem(
    new \League\Flysystem\Local\LocalFilesystemAdapter('/tmp')
);

# Init DBShenker options
$options = new \DBShenker\DBShenkerOptions(
    "CoopX", "362521879",
    "DBShenker Agency X", "347569895",
    $filesystem, 'coopx'
);

# Init sync class
$sync = new \DBShenker\DBShenkerSync($options);

# Pull then parse messages
$messages = $sync->pull()
foreach ($messages as $message) {
    $tasks = \DBShenker\DBShenker::parse($message);
    # ... do some stuff with $tasks
}

# If everything went fine, flush messages
$sync->flush();
```

#### Push to DBShenker
```php
# Init filesystem, used to sync between coop and DBShenker Agency
$filesystem = new \League\Flysystem\Filesystem(
    new \League\Flysystem\Local\LocalFilesystemAdapter('/tmp')
);

# Init DBShenker options
$options = new \DBShenker\DBShenkerOptions(
    "CoopX", "362521879",
    "DBShenker Agency X", "347569895",
    $filesystem, 'coopx'
);

# Init sync class
$sync = new \DBShenker\DBShenkerSync($options);

# Generate EDIFACT content
$message = (new \DBShenker\Generator\DBShenkerInterchange($options))
 ->addGenerator($reportA)
 ->addGenerator($reportB)
 ->generate();

# Push message to DBShenker
$sync->push($message);

```
