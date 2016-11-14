<?php
/**
 * @file
 * Contains \Drupal\styled_google_map\Plugin\views\style\StyledGoogleMapStyle.
 */
namespace Drupal\styled_google_map\Plugin\views\style;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\views\Plugin\views\style\StylePluginBase;
/**
 * Views area StyledGoogleMapStyle handler.
 *
 * @ingroup views_style_plugins
 *
 * @ViewsStyle(
 *   id = "styled_google_map",
 *   title = @Translation("Styled Google Map"),
 *   help = @Translation("Displays geofield values on the Google Map with styles."),
 *   theme = "views_view_table",
 *   display_types = {"normal"}
 *)
 */
class StyledGoogleMapStyle extends StylePluginBase {
  /**
   * Does the style plugin for itself support to add fields to it's output.
   *
   * @var bool
   */
  protected $usesFields = TRUE;

  /**
   * Does the style plugin allows to use style plugins.
   *
   * @var bool
   */
  protected $usesRowPlugin = FALSE;

  /**
   * Does the style plugin support custom css class for the rows.
   *
   * @var bool
   */
  protected $usesRowClass = FALSE;

  /**
   * Should field labels be enabled by default.
   *
   * @var bool
   */
  protected $defaultFieldLabels = FALSE;

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['styled_google_map_view_cluster_pin'] = array('default' => '');
    $options['styled_google_map_view_active_pin'] = array('default' => '');
    $options['styled_google_map_view_height'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_WIDTH);
    $options['styled_google_map_view_width'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_HEIGHT);
    $options['styled_google_map_view_style'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_STYLE);
    $options['styled_google_map_view_zoom_default'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_ZOOM);
    $options['styled_google_map_view_zoom_max'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_MAX_ZOOM);
    $options['styled_google_map_view_zoom_min'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_MIN_ZOOM);
    $options['styled_google_map_view_maptype'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_MAP_TYPE);
    $options['styled_google_map_view_maptypecontrol'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_MAP_TYPE_CONTROL);
    $options['styled_google_map_view_scalecontrol'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_SCALE_CONTROL);
    $options['styled_google_map_view_rotatecontrol'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_ROTATE_CONTROL);
    $options['styled_google_map_view_draggable'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_DRAGGABLE);
    $options['styled_google_map_view_mobile_draggable'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_MOBILE_DRAGGABLE);
    $options['styled_google_map_view_zoomcontrol'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_ZOOM_CONTROL);
    $options['styled_google_map_view_streetviewcontrol'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_STREET_VIEW_CONTROL);
    $options['styled_google_map_view_scrollwheel'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_SCROLLWHEEL);
    $options['styled_google_map_view_shadow_style'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_SHADOW_STYLE);
    $options['styled_google_map_view_padding'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_PADDING);
    $options['styled_google_map_view_border_radius'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_BORDER_RADIUS);
    $options['styled_google_map_view_border_width'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_BORDER_WIDTH);
    $options['styled_google_map_view_border_color'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_BORDER_COLOR);
    $options['styled_google_map_view_background_color'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_BACKGROUND_COLOR);
    $options['styled_google_map_view_min_width'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_MIN_WIDTH);
    $options['styled_google_map_view_max_width'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_MAX_WIDTH);
    $options['styled_google_map_view_min_height'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_MIN_HEIGHT);
    $options['styled_google_map_view_max_height'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_MAX_HEIGHT);
    $options['styled_google_map_view_auto_close'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_AUTO_CLOSE);
    $options['styled_google_map_view_arrow_size'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_ARROW_SIZE);
    $options['styled_google_map_view_arrow_position'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_ARROW_POSITION);
    $options['styled_google_map_view_arrow_style'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_ARROW_STYLE);
    $options['styled_google_map_view_disable_auto_pan'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_DISABLE_AUTO_PAN);
    $options['styled_google_map_view_hide_close_button'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_HIDE_CLOSE_BUTTON);
    $options['styled_google_map_view_disable_animation'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_DISABLE_ANIMATION);
    $options['styled_google_map_view_background_class'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_BACKGROUND_CLASS);
    $options['styled_google_map_view_content_container_class'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_CONTENT_CONTAINER_CLASS);
    $options['styled_google_map_view_arrow_class'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_ARROW_CLASS);
    $options['styled_google_map_view_arrow_outer_class'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_ARROW_OUTER_CLASS);
    $options['styled_google_map_view_arrow_inner_class'] = array('default' => STYLED_GOOGLE_MAP_DEFAULT_ARROW_INNER_CLASS);
    return $options;
  }
  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $handlers = $this->displayHandler->getHandlers('field');
    foreach ($handlers as $key => $handle) {
      // Get all location sources.
      if (!empty($handle->options['type']) && $handle->options['type'] == 'geofield_default') { //
        $data_source_options[$key] = $handle->adminLabel();
      }
      // Get all pin sources.
      if (!empty($handle->options['type']) && $handle->options['type'] == 'image') { //
        $pin_source_options[$key] = $handle->adminLabel();
      }
      // Get all popup sources
      $source_options[$key] = $handle->adminLabel();
    }
    //$source_options = array_diff($source_options, $pin_source_options, $data_source_options);

    $form['data_source'] = array(
      '#type' => 'select',
      '#title' => $this->t('Which field contains geodata?'),
      '#description' => $this->t('Needs to be a geofield.'),
      '#options' => $data_source_options,
      '#default_value' => $this->options['data_source'] ? $this->options['data_source'] : NULL,
    );
    $form['pin_source'] = array(
      '#type' => 'select',
      '#title' => $this->t('Which field contains the pin image?'),
      '#description' => $this->t('Needs to be an image field.'),
      '#options' => $pin_source_options,
      '#default_value' => $this->options['pin_source'] ? $this->options['pin_source'] : NULL,
    );
    $form['default_pin_source'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Default pin image'),
      '#default_value' => $this->options['default_pin_source'],
      '#description' => $this->t('Also you can have a default pin image for all the locations'),
    );
    $form['popup_source'] = array(
      '#type' => 'select',
      '#title' => $this->t('Which field contains the popup text?'),
      '#description' => $this->t('Can be a field or rendered entity field.'),
      '#options' => $source_options,
      '#default_value' => $this->options['popup_source'] ? $this->options['popup_source'] : NULL,
    );
    $form['category_source'] = array(
      '#type' => 'select',
      '#title' => $this->t('Which field contains the category?'),
      '#description' => $this->t('This will be used to have a class wrapper around the bubble to allow different styling per category.'),
      '#options' => $source_options,
      '#default_value' => $this->options['category_source'] ? $this->options['category_source'] : NULL,
    );
    $form['main'] = array(
      '#type' => 'details',
      '#title' => $this->t('Map Options'),
    );
    $form['main']['styled_google_map_view_height'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Height'),
      '#size' => '30',
      '#description' => $this->t('This field determines the height of the styled Google map'),
      '#default_value' => $this->options['main']['styled_google_map_view_height'],
    );
    $form['main']['styled_google_map_view_width'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Width'),
      '#size' => '30',
      '#description' => $this->t('This field determines how width the styled Google map'),
      '#default_value' => $this->options['main']['styled_google_map_view_width'],
    );
    $form['main']['styled_google_map_view_style'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Style'),
      '#description' => $this->t('The style of the map'),
      '#default_value' => $this->options['main']['styled_google_map_view_style'],
    );
    $form['main']['styled_google_map_view_cluster_pin'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Cluster pin image'),
      '#description' => $this->t('Leave empty for '),
      '#default_value' => $this->options['main']['styled_google_map_view_cluster_pin'],
    );
    $form['main']['styled_google_map_view_active_pin'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Active pin image'),
      '#default_value' => $this->options['main']['styled_google_map_view_active_pin'],
    );
    $form['main']['styled_google_map_view_maptype'] = array(
      '#type' => 'select',
      '#options' => array(
        'ROADMAP' => $this->t('ROADMAP'),
        'SATELLITE' => $this->t('SATELLITE'),
        'HYBRID' => $this->t('HYBRID'),
        'TERRAIN' => $this->t('TERRAIN'),
      ),
      '#title' => $this->t('Map type'),
      '#default_value' => $this->options['main']['styled_google_map_view_maptype'],
      '#required' => TRUE,
    );
    $form['main']['styled_google_map_view_zoom_default'] = array(
      '#type' => 'select',
      '#options' => range(1, 35),
      '#title' => $this->t('Default zoom level'),
      '#default_value' => $this->options['main']['styled_google_map_view_zoom_default'],
      '#description' => $this->t('Should be between the Min and Max zoom level.
        This will generally not working as fitbounds will try to fit all pins on the map.'),
      '#required' => TRUE,
    );
    $form['main']['styled_google_map_view_zoom_max'] = array(
      '#type' => 'select',
      '#options' => range(1, 35),
      '#title' => $this->t('Max zoom level'),
      '#default_value' => $this->options['main']['styled_google_map_view_zoom_max'],
      '#description' => $this->t('Should be greater then the Min zoom level.'),
      '#required' => TRUE,
    );
    $form['main']['styled_google_map_view_zoom_min'] = array(
      '#type' => 'select',
      '#options' => range(1, 35),
      '#title' => $this->t('Min zoom level'),
      '#default_value' => $this->options['main']['styled_google_map_view_zoom_min'],
      '#description' => $this->t('Should be smaller then the Max zoom level.'),
      '#required' => TRUE,
    );
    $form['main']['styled_google_map_view_maptypecontrol'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable Map Type control'),
      '#default_value' => $this->options['main']['styled_google_map_view_maptypecontrol'],
    );
    $form['main']['styled_google_map_view_scalecontrol'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable scale control'),
      '#default_value' => $this->options['main']['styled_google_map_view_scalecontrol'],
    );
    $form['main']['styled_google_map_view_rotatecontrol'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable rotate control'),
      '#default_value' => $this->options['main']['styled_google_map_view_rotatecontrol'],
    );
    $form['main']['styled_google_map_view_draggable'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable dragging'),
      '#default_value' => $this->options['main']['styled_google_map_view_draggable'],
    );
    $form['main']['styled_google_map_view_mobile_draggable'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable mobile dragging'),
      '#description' => $this->t('Sometimes when the map covers big part of touch device screen draggable feature can cause inability to scroll the page'),
      '#default_value' => $this->options['main']['styled_google_map_view_mobile_draggable'],
    );
    $form['main']['styled_google_map_view_streetviewcontrol'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable street view control'),
      '#default_value' => $this->options['main']['styled_google_map_view_streetviewcontrol'],
    );
    $form['main']['styled_google_map_view_zoomcontrol'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable zoom control'),
      '#default_value' => $this->options['main']['styled_google_map_view_zoomcontrol'],
    );
    $form['main']['styled_google_map_view_scrollwheel'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Enable scrollwheel'),
      '#default_value' => $this->options['main']['styled_google_map_view_scrollwheel'],
    );
    $form['popup'] = array(
      '#type' => 'details',
      '#title' => $this->t('Popup Styling'),
      '#description' => $this->t('All settings for the popup exposed by the library. If you want more flexibility in your the styling of the popup. You can use the CSS defined classes'),
    );
    $form['popup']['styled_google_map_view_shadow_style'] = array(
      '#type' => 'select',
      '#title' => $this->t('Shadow style'),
      '#options' => array(0, 1, 2),
      '#description' => $this->t('1: shadow behind, 2: shadow below, 0: no shadow'),
      '#default_value' => $this->options['popup']['styled_google_map_view_shadow_style'],
    );
    $form['popup']['styled_google_map_view_padding'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Padding'),
      '#field_suffix' => 'px',
      '#default_value' => $this->options['popup']['styled_google_map_view_padding'],
    );
    $form['popup']['styled_google_map_view_border_radius'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Border radius'),
      '#field_suffix' => 'px',
      '#default_value' => $this->options['popup']['styled_google_map_view_border_radius'],
    );
    $form['popup']['styled_google_map_view_border_width'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Border width'),
      '#field_suffix' => 'px',
      '#default_value' => $this->options['popup']['styled_google_map_view_border_width'],
    );
    $form['popup']['styled_google_map_view_border_color'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Border color'),
      '#field_suffix' => '#hex',
      '#default_value' => $this->options['popup']['styled_google_map_view_border_color'],
    );
    $form['popup']['styled_google_map_view_background_color'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Background color'),
      '#field_suffix' => '#hex',
      '#default_value' => $this->options['popup']['styled_google_map_view_background_color'],
    );
    $form['popup']['styled_google_map_view_min_width'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Min width'),
      '#field_suffix' => 'px (or auto)',
      '#default_value' => $this->options['popup']['styled_google_map_view_min_width'],
    );
    $form['popup']['styled_google_map_view_max_width'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Max width'),
      '#field_suffix' => 'px (or auto)',
      '#default_value' => $this->options['popup']['styled_google_map_view_max_width'],
    );
    $form['popup']['styled_google_map_view_min_height'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Min height'),
      '#field_suffix' => 'px (or auto)',
      '#default_value' => $this->options['popup']['styled_google_map_view_min_height'],
    );
    $form['popup']['styled_google_map_view_max_height'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Max height'),
      '#field_suffix' => 'px (or auto)',
      '#default_value' => $this->options['popup']['styled_google_map_view_max_height'],
    );
    $form['popup']['styled_google_map_view_arrow_style'] = array(
      '#type' => 'select',
      '#title' => $this->t('Arrow style'),
      '#options' => array(0, 1, 2),
      '#description' => $this->t('1: left side visible, 2: right side visible, 0: both sides visible'),
      '#default_value' => $this->options['popup']['styled_google_map_view_arrow_style'],
    );
    $form['popup']['styled_google_map_view_arrow_size'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Arrow size'),
      '#field_suffix' => 'px',
      '#default_value' => $this->options['popup']['styled_google_map_view_arrow_size'],
    );
    $form['popup']['styled_google_map_view_arrow_position'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Arrow position'),
      '#field_suffix' => 'px',
      '#default_value' => $this->options['popup']['styled_google_map_view_arrow_position'],
    );
    $form['popup']['styled_google_map_view_disable_auto_pan'] = array(
      '#type' => 'select',
      '#title' => $this->t('Auto pan'),
      '#options' => array(true => $this->t('Yes'), false => $this->t('No')),
      '#description' => $this->t('Automatically center the pin on click'),
      '#default_value' => $this->options['popup']['styled_google_map_view_disable_auto_pan'],
    );
    $form['popup']['styled_google_map_view_hide_close_button'] = array(
      '#type' => 'select',
      '#title' => $this->t('Hide close button'),
      '#options' => array(true => $this->t('Yes'), false => $this->t('No')),
      '#description' => $this->t('Hide the popup close button'),
      '#default_value' => $this->options['popup']['styled_google_map_view_hide_close_button'],
    );
    $form['popup']['styled_google_map_view_disable_animation'] = array(
      '#type' => 'select',
      '#title' => $this->t('Disable animation'),
      '#options' => array(true => $this->t('Yes'), false => $this->t('No')),
      '#description' => $this->t('Disables the popup animation'),
      '#default_value' => $this->options['popup']['styled_google_map_view_disable_animation'],
    );
    $form['popup_classes'] = array(
      '#type' => 'details',
      '#title' => $this->t('Popup classes'),
      '#description' => $this->t('CSS classes for easy popup styling'),
    );
    $form['popup_classes']['styled_google_map_view_content_container_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Wrapper class'),
      '#default_value' => $this->options['popup_classes']['styled_google_map_view_content_container_class'],
    );
    $form['popup_classes']['styled_google_map_view_background_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Background class'),
      '#default_value' => $this->options['popup_classes']['styled_google_map_view_background_class'],
    );
    $form['popup_classes']['styled_google_map_view_arrow_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Arrow class'),
      '#default_value' => $this->options['popup_classes']['styled_google_map_view_arrow_class'],
    );
    $form['popup_classes']['styled_google_map_view_arrow_outer_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Arrow outer class'),
      '#default_value' => $this->options['popup_classes']['styled_google_map_view_arrow_outer_class'],
    );
    $form['popup_classes']['styled_google_map_view_arrow_inner_class'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Arrow inner class'),
      '#default_value' => $this->options['popup_classes']['styled_google_map_view_arrow_inner_class'],
    );
  }
  /**
   * {@inheritdoc}
   */
  public function render($results = FALSE) {
    // We check if the views result are empty, or if the settings of this area
    // force showing this area even if the view is empty.
    if (!empty($this->view->live_preview)) {
        $output['preview'] = array(
            '#markup' => '<p>'.$this->t('This is a preview of styled google map plugin. No map is displayed.').'</p>',
        );
        $output['maps'] = array(
            '#markup' => '<p>'.$this->t('This map has @num points', array('@num' => count($results))).'</p>',
        );
        return $output;
    }
    if (!empty($results)) {
        // Get all geofield locations.
        $locations = array();
        foreach ($results as $row) {
            $location = array();
            if (isset($this->options['data_source']) && !empty($this->options['data_source'])) {
                // Add geofield data.
                if (!$row->_entity->get($this->options['data_source'])->isEmpty()) {
                    $point = $row->_entity->get($this->options['data_source'])->getValue();
                    $location = $location + $point[0];
                } else {
                    break;
                }
                // Add pin image url.
                if (isset($this->options['pin_source']) && !empty($this->options['pin_source'])) {
                    if (!$row->_entity->get($this->options['pin_source'])->isEmpty()) {
                        $image = $row->_entity->get($this->options['pin_source'])->first()->get('entity')->getTarget()->getValue();
                        $location = $location + array(
                            'pin' => $image->url(),
                        );
                        // Add the active pin image.
                        if (!$this->options['main']['styled_google_map_view_active_pin']) {
                            $location = $location + array(
                                'active_pin' => $image->url(),
                            );
                        } else {
                            $location = $location + array(
                                'active_pin' => file_create_url($this->options['main']['styled_google_map_view_active_pin'])
                            );
                        }
                    } elseif (!empty($this->options['default_pin_source'])) {
                        $location['pin'] = file_create_url($this->options['default_pin_source']);
                    }
                }
                // Add pin popup html.
                if (isset($this->options['popup_source']) && !empty($this->options['popup_source'])) {
                    if (!$row->_entity->get($this->options['popup_source'])->isEmpty()) {
                        $popup = $row->_entity->{$this->options['popup_source']}->view();
                        $location = $location + array(
                            'popup' => render($popup),
                        );
                    }
                }
                // Add category.
                if (isset($this->options['category_source']) && !empty($this->options['category_source'])) {
                    if (!$row->_entity->get($this->options['category_source'])->isEmpty()) {
                        if (\Drupal::moduleHandler()->moduleExists('pathauto')) {
                            $category = \Drupal\pathauto\AliasCleaner::cleanstring($row->_entity->{$this->options['category_source']}->view());
                        } else {
                            $category = $row->_entity->{$this->options['category_source']}->view();
                        }
                        $location = $location + array(
                            'category' => render($category),
                        );
                    }
                }
            }
            if ($location) {
                $locations[] = $location;
            }
        }
        // Add custom settings.
        $cluster_pin_image = '';
        $active_pin_image = '';
        if ($this->options['styled_google_map_view_cluster_pin']) {
            $cluster_pin_image = file_create_url($this->options['styled_google_map_view_cluster_pin']);
        }
        //TODO: sanitize all options.
        $map_settings = array(
            'id' => 'map_'.$this->view->dom_id,
            'locations' => $locations,
            'settings' => array(
                'height' => $this->options['main']['styled_google_map_view_height'],
                'width' => $this->options['main']['styled_google_map_view_width'],
                'maptypecontrol' => $this->options['main']['styled_google_map_view_maptypecontrol'],
                'scalecontrol' => $this->options['main']['styled_google_map_view_scalecontrol'],
                'rotatecontrol' => $this->options['main']['styled_google_map_view_rotatecontrol'],
                'draggable' => $this->options['main']['styled_google_map_view_draggable'],
                'mobile_draggable' => $this->options['main']['styled_google_map_view_mobile_draggable'],
                'scrollwheel' => $this->options['main']['styled_google_map_view_scrollwheel'],
                'streetviewcontrol' => $this->options['main']['styled_google_map_view_streetviewcontrol'],
                'style' => array(
                    'maptype' => $this->options['main']['styled_google_map_view_maptype'],
                    'style' => $this->options['main']['styled_google_map_view_style'],
                    'active_pin' => $active_pin_image,
                    'cluster_pin' => $cluster_pin_image,
                ),
                'zoom' => array(
                    'default' => $this->options['main']['styled_google_map_view_zoom_default'],
                    'max' => $this->options['main']['styled_google_map_view_zoom_max'],
                    'min' => $this->options['main']['styled_google_map_view_zoom_min'],
                ),
                'zoomcontrol' => $this->options['main']['styled_google_map_view_zoomcontrol'],
                'popup' => array(
                    'disable_animation' => $this->options['popup']['styled_google_map_view_disable_animation'] ? true : false,
                    'disable_autopan' => $this->options['popup']['styled_google_map_view_disable_auto_pan'] ? true : false,
                    'hide_close_button' => $this->options['popup']['styled_google_map_view_hide_close_button'] ? true : false,
                    'shadow_style' => $this->options['popup']['styled_google_map_view_shadow_style'],
                    'padding' => $this->options['popup']['styled_google_map_view_padding'],
                    'border_radius' => $this->options['popup']['styled_google_map_view_border_radius'],
                    'border_width' => $this->options['popup']['styled_google_map_view_border_width'],
                    'border_color' => $this->options['popup']['styled_google_map_view_border_color'],
                    'background_color' => $this->options['popup']['styled_google_map_view_background_color'],
                    'min_width' => $this->options['popup']['styled_google_map_view_min_width'],
                    'max_width' => $this->options['popup']['styled_google_map_view_max_width'],
                    'min_height' => $this->options['popup']['styled_google_map_view_min_height'],
                    'max_height' => $this->options['popup']['styled_google_map_view_max_height'],
                    'arrow_style' => $this->options['popup']['styled_google_map_view_arrow_style'],
                    'arrow_size' => $this->options['popup']['styled_google_map_view_arrow_size'],
                    'arrow_position' => $this->options['popup']['styled_google_map_view_arrow_position'],
                    'classes' => array(
                        'container' => $this->options['popup_classes']['styled_google_map_view_content_container_class'],
                        'background' => $this->options['popup_classes']['styled_google_map_view_background_class'],
                        'arrow' => $this->options['popup_classes']['styled_google_map_view_arrow_class'],
                        'arrow_outer' => $this->options['popup_classes']['styled_google_map_view_arrow_outer_class'],
                        'arrow_inner' => $this->options['popup_classes']['styled_google_map_view_arrow_inner_class'],
                    ),
                ),
            ),
        );
    }  
    $output = array();
    $output['#attached']['drupalSettings']['styled_google_map'] = array('map_'.$this->view->dom_id => 'map_'.$this->view->dom_id);
    $output['#attached']['drupalSettings']['maps'] = array('idmap_'.$this->view->dom_id => $map_settings);
    // Output a div placeholder for the Styled Google Map.
    $output['styled_google_map']['#markup'] = '<div class="styled_map" id="map_'.$this->view->dom_id.'"></div>';
    // Attach the Styled Google Map javascript file.
    $output['#attached']['library'][] = 'styled_google_map/styled-google-map';
    return $output;
  }
}
