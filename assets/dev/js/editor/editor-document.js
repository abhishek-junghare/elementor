import DocumentComponent from './document/component';

elementorCommon.elements.$window.on( 'elementor:init-components', () => {
	$e.components.register( new DocumentComponent() );

	// TODO: Remove, BC Since 2.9.0.
	elementor.saver = $e.components.get( 'document/save' );
} );

$e.modules.document = DocumentComponent.getModules();
