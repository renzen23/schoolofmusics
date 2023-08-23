<?php
// Don't load directly
defined( 'WPINC' ) or die;

/**
 * Event Submission Form Metabox For Custom Fields
 * This is used to add a metabox to the event submission form to allow for custom
 * field input for user submitted events.
 *
 * Override this template in your own theme by creating a file at
 * [your-theme]/tribe-events/community/modules/custom.php
 *
 * @since  2.1
 * @version 4.5.9
 */
// Makes sure we dont even try when Pro is inactive
if ( ! class_exists( 'Tribe__Events__Pro__Main' ) ) {
	return;
}

$fields = tribe_get_option( 'custom-fields' );

if ( empty( $fields ) || ! is_array( $fields ) ) {
	return;
}

$post_id = get_the_ID();
?>

<div class="tribe-section tribe-section-custom-fields">
	<div class="tribe-section-header">
		<h3><?php esc_html_e( 'Additional Fields', 'tribe-events-community' ); ?></h3>
	</div>

	<?php
		/**
		 * Allow developers to hook and add content to the begining of this section.
		 *
		 * @param int $post_id The post ID of the event.
		 */
		do_action( 'tribe_events_community_section_before_custom_fields', $post_id );
	?>

	<table class="tribe-section-content">
		<colgroup>
			<col class="tribe-colgroup tribe-colgroup-label">
			<col class="tribe-colgroup tribe-colgroup-field">
		</colgroup>

		<?php foreach ( $fields as $field ) : ?>
			<?php
				$value = get_post_meta( get_the_ID(), $field['name'], true );

				// Possibly save field value on failed form submit so user doesn't have to re-enter.
				if ( empty( $value ) && ! empty( $_POST[ $field['name'] ] ) ) {
					$value = $_POST[ $field['name'] ];
				}

				/**
				 * Allows setting and/or modifying the field's value.
				 *
				 * @param mixed $value The existing field value.
				 * @param string $name The field name.
				 * @param int $event_id The event's post ID.
				 */
				$value = apply_filters( 'tribe_events_community_custom_field_value', $value, $field['name'], get_the_ID() );

				switch ( $field['type'] ) {

					case 'checkbox' :
					case 'radio' :
					case 'dropdown' :

						// Handle values that may be a "|"-delimited string.
						if ( ! is_array( $value ) ) {
							$value = explode( '|', $value );
						}

						// Handle the value in its more common form as an array.
						$value = array_filter( $value );
 						$value = array_map( 'trim', $value );
 						$value = array_map( 'esc_attr', $value );

						break;

					case 'textarea' :

						$value = is_array( $value ) ? $value[0] : $value;
						$value = esc_textarea( trim( stripslashes( $value ) ) );
						break;

					case 'text' :
					case 'url' :

						$value = is_array( $value ) ? $value[0] : $value;
						$value = esc_textarea( trim( stripslashes( $value ) ) );
						break;
				}

				$field_id = sanitize_html_class( 'tribe_custom_' . $field['label'] );

				if ( in_array( $field['type'], array( 'radio', 'dropdown', 'checkbox' ) ) ) {
					$field['name'] = stripslashes( $field['name'] );
				}

				$field['name'] = esc_attr( $field['name'] );

				// Configure options
				$options = array();

				// Add Blank None option for Radio and Dropdown
				if ( in_array( $field['type'], array( 'radio', 'dropdown' ) ) ) {
					$options[''] = __( 'None', 'tribe-events-community' );
				}

				// Options defined in the panel
				$options = array_merge( $options, explode( "\n", $field['values'] ) );
				$options = array_map( 'trim', $options );
				$options = array_map( 'esc_attr', $options );

				$field_classes[] = 'tribe-section-content-row';
				$field_classes[] = sanitize_html_class( 'tribe-field-type-' . $field['type'] );
			?>
			<tr class="<?php echo implode( ' ', $field_classes ); ?>">
				<td class="tribe-section-content-label">
					<?php tribe_community_events_field_label( $field['name'], sprintf( _x( '%s:', 'custom field label', 'tribe-events-community' ), $field['label'] ) ); ?>
				</td>
				<td class="tribe-section-content-field">
					<?php if ( 'text' === $field['type'] ) : ?>
						<input
							type="text"
							id="<?php echo $field_id; ?>"
							name="<?php echo $field['name']; ?>"
							value="<?php echo $value; ?>"
						>
					<?php elseif ( 'url' === $field['type'] ) : ?>
						<input
							type="url"
							id="<?php echo $field_id; ?>"
							name="<?php echo $field['name']; ?>"
							value="<?php echo $value; ?>"
						>
					<?php elseif ( 'radio' === $field['type'] ) : ?>
						<?php foreach ( $options as $option ) : ?>
							<label>
								<input
									type="radio"
									name="<?php echo $field['name']; ?>"
									value="<?php echo $option; ?>"
									<?php checked( in_array( $option, (array) $value ) ); ?>
								>
								<?php echo stripslashes( $option ); ?>
							</label>
						<?php endforeach; ?>
					<?php elseif ( 'checkbox' === $field['type'] ) : ?>
						<?php foreach ( $options as $option ) : ?>
							<label>
								<input
									type="checkbox"
									value="<?php echo $option; ?>"
									<?php checked( in_array( $option, (array) $value ) ); ?>
									name="<?php echo $field['name']; ?>[]"
								>
								<?php echo stripslashes( $option ); ?>
							</label>
						<?php endforeach; ?>
					<?php elseif ( 'dropdown' === $field['type'] ) : ?>
						<select name="<?php echo $field['name']; ?>">
							<?php foreach ( $options as $option ) : ?>
								<option
									value="<?php echo $option; ?>"
									<?php selected( in_array( $option, (array) $value ) ); ?>
								>
									<?php echo stripslashes( $option ); ?>
								</option>
							<?php endforeach; ?>
						</select>
					<?php elseif ( 'textarea' === $field['type'] ) : ?>
						<textarea
							id="<?php echo $field_id; ?>"
							name="<?php echo $field['name']; ?>"
						><?php echo $value; ?></textarea>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</table>

	<?php
		/**
		 * Allow developers to hook and add content to the end of this section.
		 *
		 * @param int $post_id The post ID of the event.
		 */
		do_action( 'tribe_events_community_section_after_custom_fields', $post_id );
	?>
</div>