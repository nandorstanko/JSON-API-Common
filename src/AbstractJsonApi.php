<?php
declare(strict_types=1);

namespace Enm\JsonApi;

use Enm\JsonApi\Model\Factory\DocumentFactory;
use Enm\JsonApi\Model\Factory\DocumentFactoryInterface;
use Enm\JsonApi\Model\Factory\ResourceFactory;
use Enm\JsonApi\Model\Factory\ResourceFactoryAwareInterface;
use Enm\JsonApi\Model\Factory\ResourceFactoryInterface;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use Enm\JsonApi\Serializer\Deserializer;
use Enm\JsonApi\Serializer\DocumentDeserializerInterface;
use Enm\JsonApi\Serializer\DocumentSerializerInterface;
use Enm\JsonApi\Serializer\Serializer;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
abstract class AbstractJsonApi implements ResourceFactoryAwareInterface, JsonApiInterface
{
    /**
     * @var DocumentFactoryInterface
     */
    private $documentFactory;

    /**
     * @var ResourceFactoryInterface
     */
    private $resourceFactory;

    /**
     * @var DocumentSerializerInterface
     */
    private $documentSerializer;

    /**
     * @var DocumentDeserializerInterface
     */
    private $documentDeserializer;

    /**
     * @param string $type
     * @param string $id
     * @return ResourceInterface
     */
    public function resource(string $type, string $id): ResourceInterface
    {
        return $this->resourceFactory()->create($type, $id);
    }

    /**
     * @param ResourceInterface|null $resource
     * @return DocumentInterface
     */
    public function singleResourceDocument(ResourceInterface $resource = null): DocumentInterface
    {
        return $this->documentFactory()->create($resource);
    }

    /**
     * @param array $resource
     * @return DocumentInterface
     */
    public function multiResourceDocument(array $resource = []): DocumentInterface
    {
        return $this->documentFactory()->create($resource);
    }

    /**
     * @param DocumentInterface $document
     * @return array
     */
    public function serializeDocument(DocumentInterface $document): array
    {
        return $this->documentSerializer()->serializeDocument($document);
    }

    /**
     * @param array $document
     * @return DocumentInterface
     */
    public function deserializeDocument(array $document): DocumentInterface
    {
        return $this->documentDeserializer()->deserializeDocument($document);
    }

    /**
     * @param DocumentFactoryInterface $documentFactory
     *
     * @return void
     */
    public function setDocumentFactory(DocumentFactoryInterface $documentFactory)
    {
        $this->documentFactory = $documentFactory;
    }

    /**
     * @param ResourceFactoryInterface $resourceFactory
     *
     * @return void
     */
    public function setResourceFactory(ResourceFactoryInterface $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @param DocumentSerializerInterface $documentSerializer
     *
     * @return void
     */
    public function setDocumentSerializer(DocumentSerializerInterface $documentSerializer)
    {
        $this->documentSerializer = $documentSerializer;
    }

    /**
     * @param DocumentDeserializerInterface $documentDeserializer
     *
     * @return void
     */
    public function setDocumentDeserializer(DocumentDeserializerInterface $documentDeserializer)
    {
        $this->documentDeserializer = $documentDeserializer;
    }

    /**
     * @return DocumentFactoryInterface
     */
    private function documentFactory(): DocumentFactoryInterface
    {
        if (!$this->documentFactory instanceof DocumentFactoryInterface) {
            $this->documentFactory = new DocumentFactory();
        }
        $this->documentFactory()->setResourceFactory($this->resourceFactory());

        return $this->documentFactory;
    }

    /**
     * @return ResourceFactoryInterface
     */
    private function resourceFactory(): ResourceFactoryInterface
    {
        if (!$this->resourceFactory instanceof ResourceFactoryInterface) {
            $this->resourceFactory = new ResourceFactory();
        }

        return $this->resourceFactory;
    }


    /**
     * @return DocumentSerializerInterface
     */
    private function documentSerializer(): DocumentSerializerInterface
    {
        if (!$this->documentSerializer instanceof DocumentSerializerInterface) {
            $this->documentSerializer = new Serializer();
        }

        return $this->documentSerializer;
    }

    /**
     * @return DocumentDeserializerInterface
     */
    private function documentDeserializer(): DocumentDeserializerInterface
    {
        if (!$this->documentDeserializer instanceof DocumentDeserializerInterface) {
            $this->documentDeserializer = new Deserializer($this->documentFactory(), $this->resourceFactory());
        }

        return $this->documentDeserializer;
    }
}