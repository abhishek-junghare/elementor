<?php

namespace Elementor\Modules\AtomicWidgets\PropTypes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

abstract class Prop_Type implements \JsonSerializable {

	protected $default = null;

	protected array $settings = [];

	protected array $meta = [];

	/**
	 * @var array<Prop_Type>
	 */
	protected array $additional_types = [];

	abstract public static function get_key(): string;

	abstract public function validate( $value ): void;

	/**
	 * @return $this
	 */
	public static function make(): self {
		return new static();
	}

	public function default( $default ): self {
		$this->default = $default;

		return $this;
	}

	public function get_default() {
		return $this->default;
	}

	public function get_settings() {
		return $this->settings;
	}

	public function add_meta( $key_or_tuple, $value = null ): self {
		$is_tuple = (
			is_array( $key_or_tuple ) &&
			2 === count( $key_or_tuple ) &&
			is_string( $key_or_tuple[0] )
		);

		if ( $is_tuple ) {
			[ $key, $value ] = $key_or_tuple;

			$this->meta[ $key ] = $value;

			return $this;
		}

		if ( is_string( $key_or_tuple ) ) {
			$this->meta[ $key_or_tuple ] = $value;
		}

		return $this;
	}

	public function get_meta( string $key ) {
		return $this->meta[ $key ] ?? null;
	}

	public function additional_type( Prop_Type $type ): self {
		$this->additional_types[] = $type;

		return $this;
	}

	public function get_additional_types(): array {
		return $this->additional_types;
	}

	public function jsonSerialize(): array {
		$additional_types = array_map( function ( Prop_Type $type ) {
			return [
				'key' => $type::get_key(),
				'settings' => (object) $type->get_settings(),
			];
		}, $this->additional_types );

		return [
			'type' => [
				'key' => static::get_key(),
				'default' => $this->get_default(),
				'settings' => (object) $this->get_settings(),
			],
			'additional_types' => $additional_types,
		];
	}
}
