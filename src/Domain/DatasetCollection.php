<?php


namespace PCextreme\FreeNAS\Domain;


use PCextreme\FreeNAS\Exceptions\NotFoundException;

class DatasetCollection
{
    /** @var array<Dataset> */
    private $collection;

    /** @param array<Dataset> */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    public static function fromArray(array $data): DatasetCollection
    {
        $collection = array_filter(
            $data['children'] ?? [],
            function ($dataset) {
                return $dataset['type'] === Dataset::TYPE_FILESYSTEM;
            }
        );
        $collection = array_map(
            function (array $dataset) {
                return Dataset::fromArray($dataset);
            },
            $collection
        );

        return new DatasetCollection($collection);
    }

    public function getDataset(string $datasetId): Dataset
    {
        foreach ($this->collection as $dataset) {
            if ($dataset->getId() === $datasetId) {
                return $dataset;
            }
        }

        throw new NotFoundException("Could not find dataset with id [$datasetId] in datasets");
    }
}
