<?php
namespace craft\gql\types;

use craft\gql\directives\FormatDateTime;
use craft\gql\GqlEntityRegistry;
use GraphQL\Language\AST\StringValueNode;
use GraphQL\Type\Definition\ScalarType;

/**
 * Class DateTime
 */
class DateTimeType extends ScalarType
{
    /**
     * @var string
     */
    public $name = 'DateTime';

    /**
     * @var string
     */
    public $description = 'The `DateTime` scalar type represents a point in time.';

    public function __construct(array $config = [])
    {
        $config['resolve'] = function () {
            return 'ok;';
        };
        parent::__construct($config);
    }

    /**
     * Returns a singleton instance to ensure one type per schema.
     *
     * @return DateTimeType
     */
    public static function getType(): DateTimeType
    {
        return GqlEntityRegistry::getEntity(self::class) ?: GqlEntityRegistry::createEntity(self::class, new self());
    }

    /**
     *
     * @return string
     */
    public static function getName(): string
    {
        return 'DateTime';
    }

    /**
     * @inheritdoc
     */
    public function serialize($value)
    {
        // The value not being a datetime would indicate an already formatted date.
        if ($value instanceof \DateTime) {
            $value->setTimezone(new \DateTimeZone(FormatDateTime::DEFAULT_TIMEZONE));
            $value = $value->format(FormatDateTime::DEFAULT_FORMAT);
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function parseValue($value)
    {
        return (string) $value;
    }

    /**
     * @inheritdoc
     */
    public function parseLiteral($valueNode, array $variables = null)
    {
        if ($valueNode instanceof StringValueNode) {
            return (string) $valueNode->value;
        }

        // Intentionally without message, as all information already in wrapped Exception
        throw new \Exception();
    }
}