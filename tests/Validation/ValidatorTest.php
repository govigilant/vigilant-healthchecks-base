<?php

namespace Vigilant\HealthChecksBase\Tests\Validation;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use stdClass;
use Vigilant\HealthChecksBase\Enums\Status;
use Vigilant\HealthChecksBase\Validation\ValidationException;
use Vigilant\HealthChecksBase\Validation\Validator;

class ValidatorTest extends TestCase
{
    #[Test]
    public function it_passes_when_no_rules_are_defined(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::validate(['anything' => 'goes'], []);
    }

    #[Test]
    public function it_requires_attributes_marked_as_required(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field is required.');

        Validator::validate(['age' => 30], ['name' => ['required']]);
    }

    #[Test]
    public function it_ignores_value_rules_when_attribute_is_absent(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::validate(['age' => 30], ['nickname' => ['string']]);
    }

    #[Test]
    public function it_rejects_null_values_when_nullable_rule_is_missing(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field may not be null.');

        Validator::validate(['name' => null], ['name' => ['required', 'string']]);
    }

    #[Test]
    public function it_allows_null_values_when_nullable_rule_is_present(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::validate(['description' => null], ['description' => ['nullable', 'string']]);
    }

    #[Test]
    public function it_validates_string_values(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The name field failed the string validation rule.');

        Validator::validate(['name' => 123], ['name' => ['required', 'string']]);
    }

    #[Test]
    public function it_validates_numeric_values(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The amount field failed the numeric validation rule.');

        Validator::validate(['amount' => 'not-a-number'], ['amount' => ['numeric']]);
    }

    #[Test]
    public function it_validates_integer_values(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The count field failed the integer validation rule.');

        Validator::validate(['count' => '5'], ['count' => ['integer']]);
    }

    #[Test]
    public function it_validates_array_values(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The items field failed the array validation rule.');

        Validator::validate(['items' => 'not-an-array'], ['items' => ['array']]);
    }

    #[Test]
    public function it_validates_min_rule_constraints(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The score field failed the min:5 validation rule.');

        Validator::validate(['score' => 1], ['score' => ['min:5']]);
    }

    #[Test]
    public function it_accepts_values_passing_min_rule(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::validate(['score' => 10], ['score' => ['min:5']]);
    }

    #[Test]
    public function it_accepts_valid_enum_instances(): void
    {
        $this->expectNotToPerformAssertions();

        Validator::validate(['status' => Status::Healthy], ['status' => ['enum:'.Status::class]]);
    }

    #[Test]
    public function it_rejects_invalid_enum_values(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('The status field failed the enum validation rule.');

        Validator::validate(['status' => 'healthy'], ['status' => ['enum:'.Status::class]]);
    }

    #[Test]
    public function it_rejects_non_string_rule_definitions(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid validation rule definition [stdClass].');

        Validator::validate(['name' => 'John'], ['name' => ['required', new stdClass]]);
    }

    #[Test]
    public function it_rejects_unknown_rule_definitions(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Unknown validation rule [bogus] for name.');

        Validator::validate(['name' => 'John'], ['name' => ['bogus']]);
    }

    #[Test]
    public function it_validates_min_rule_definitions_are_numeric(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid validation rule definition [min:abc].');

        Validator::validate(['score' => 10], ['score' => ['min:abc']]);
    }

    #[Test]
    public function it_validates_enum_rule_definitions_refer_to_existing_enums(): void
    {
        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Invalid validation rule definition [enum:MissingStatus].');

        Validator::validate(['status' => Status::Healthy], ['status' => ['enum:MissingStatus']]);
    }
}
