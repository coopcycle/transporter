# DBShenker client


### Exemples

```php
const FILE = './SCONTR.txt';

/** @var \DBShenker\Parser\DBShenkerScontrParser $t */
$t = DBShenker::parse(FILE);
//$t->setAddressGeocoder(new GoogleMapsAddressGeocoder(getenv('MAPS_KEY')));


foreach ($t->getTasks() as $task) {
    print_r("Task ID: ".$task->getID()."\n");
    print_r("Recipient address: ".$task->getNamesAndAddresses(NameAndAddressType::RECIPIENT)[0]->getAddress()."\n");
    print_r("Number of packages: ".count($task->getPackages())."\n");
    print_r("Total weight: ".array_sum(array_map(fn(Mesurement $p) => $p->getQuantity() ,$task->getMesurements()))." kg\n");
    print_r("Comments: ".$task->getComments()."\n");
    print_r("\n\n");
}
```
