<?php

/**
 * @file
 * Contains \Drupal\styled_google_map\Plugin\Field\FieldFormatter\StyledGoogleMapDefaultFormatter.
 */

namespace Drupal\styled_google_map\Plugin\Field\FieldFormatter;

use Drupal\Core\Url;
use Drupal\field\Entity\FieldConfig;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'styled_google_map_default' formatter.
 *
 * @FieldFormatter(
 *   id = "styled_google_map_default",
 *   label = @Translation("Styled Google Map"),
 *   field_types = {
 *     "geofield"
 *   }
 * )
 */
class StyledGoogleMapDefaultFormatter extends FormatterBase {
  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return array(
      'width' => STYLED_GOOGLE_MAP_DEFAULT_WIDTH,
      'height' => STYLED_GOOGLE_MAP_DEFAULT_HEIGHT,
      'style' => array(
        'maptype' => STYLED_GOOGLE_MAP_DEFAULT_MAP_TYPE,
        'style' => '',
        'pin' => STYLED_GOOGLE_MAP_DEFAULT_PIN
      ),
      'popup' => array(
        'choice' => '',
        'label' => STYLED_GOOGLE_MAP_DEFAULT_LABEL,
      ),
      'zoom' => array(
        'default' => STYLED_GOOGLE_MAP_DEFAULT_ZOOM,
        'min' => STYLED_GOOGLE_MAP_DEFAULT_MAX_ZOOM,
        'max' => STYLED_GOOGLE_MAP_DEFAULT_MIN_ZOOM
      ),
      'maptypecontrol' => STYLED_GOOGLE_MAP_DEFAULT_MAP_TYPE_CONTROL,
      'pancontrol' => STYLED_GOOGLE_MAP_DEFAULT_PAN_CONTROL,
      'streetviewcontrol' => STYLED_GOOGLE_MAP_DEFAULT_ZOOM_CONTROL,
      'zoomcontrol' => STYLED_GOOGLE_MAP_DEFAULT_STREET_VIEW_CONTROL,
      'scrollwheel' => STYLED_GOOGLE_MAP_DEFAULT_SCROLLWHEEL
    );
  }
  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = parent::settingsForm($form, $form_state);
    $default_settings = StyledGoogleMapDefaultFormatter::defaultSettings();
    // Set all available setting fields for the Styled Google Map.
    //if ($display['type'] == 'styled_google_map_map_formatter') {
      $elements['width'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Width size'),
        '#default_value' => $this->getSetting('width'),
        '#description' => $this->t('Map width written in pixels or percentage'),
        '#required' => TRUE,
      );
      $elements['height'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('Height size'),
        '#default_value' => $this->getSetting('height'),
        '#description' => $this->t('Map height written in pixels or percentage'),
        '#required' => TRUE,
      );
      $elements['style'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Map style'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $style_settings = $this->getSetting('style');
      $elements['style']['maptype'] = array(
        '#type' => 'select',
        '#options' => array(
          'ROADMAP' => $this->t('ROADMAP'),
          'SATELLITE' => $this->t('SATELLITE'),
          'HYBRID' => $this->t('HYBRID'),
          'TERRAIN' => $this->t('TERRAIN'),
        ),
        '#title' => $this->t('Map type'),
        '#default_value' => empty($style_settings['maptype']) ? $default_settings['style']['maptype'] : $style_settings['maptype'],
        '#required' => TRUE,
      );
      $elements['style']['style'] = array(
        '#type' => 'textarea',
        '#title' => $this->t('JSON Style'),
        '#default_value' => empty($style_settings['style']) ? $default_settings['style']['style'] : $style_settings['style'],
        '#description' => $this->t('Check out !url for custom styles. Also check out this !project to style and edit Google Map JSON styles.', array(
            '!url' => \Drupal::l($this->t('Snazzy maps'), Url::fromUri('http://snazzymaps.com/')),
            '!project' => \Drupal::l($this->t('Github page'), Url::fromUri('http://instrument.github.io/styled-maps-wizard/')))
        ),
      );
      $elements['style']['pin'] = array(
        '#type' => 'textfield',
        '#title' => $this->t('URL to the marker'),
        '#default_value' => empty($style_settings['pin']) ? $default_settings['style']['pin'] : $style_settings['pin'],  
        '#description' => $this->t('URL to the marker image. You can use a !wrapper for the url. Ex. !example (not working until !fixed)',
          array(
            '!wrapper' => \Drupal::l($this->t('Stream wrapper'),
            Url::fromUri('https://drupal.org/project/system_stream_wrapper')),
            '!example' => STYLED_GOOGLE_MAP_DEFAULT_PIN,
            '!fixed' => \Drupal::l('https://www.drupal.org/node/1308152', Url::fromUri('https://www.drupal.org/node/1308152')))
        ),
      );
      $elements['popup'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Marker popup'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $popup_settings = $this->getSetting('popup');
      $elements['popup']['choice'] = array(
        '#type' => 'select',
        '#options' => array(0 => $this->t('None'), 1 => $this->t('Field'), 2 => $this->t('View mode')),
        '#default_value' => empty($popup_settings['choice']) ? $default_settings['popup']['choice'] : $popup_settings['choice'],  
        '#id' => 'edit-popup-choice-field',
      );
      // Retrieve view mode settings from the current entity bundle.      
      $view_modes = \Drupal::entityManager()->getViewModeOptions($form['#entity_type']);
      $elements['popup']['view_mode'] = array(
        '#type' => 'select',
        '#options' => $view_modes,
        '#default_value' => $this->getSetting('popup')['view_mode'],
        '#states' => array(
          'visible' => array(
            ':input[id="edit-popup-choice-field"]' => array('value' => 2),
          ),
        ),
      );
      // Retrieve all field names from the current entity bundle.
      $field_options = array();
      $fields = $form['#fields'];
      foreach ($fields as $field) {
        $field_options[$field] = FieldConfig::loadByName($form['#entity_type'], $form['#bundle'], $field)->getLabel();
      }       
      $elements['popup']['text'] = array(
        '#type' => 'select',
        '#options' => $field_options,
        '#default_value' => empty($popup_settings['text']) ? $default_settings['popup']['text'] : $popup_settings['text'],  
        '#states' => array(
          'visible' => array(
            ':input[id="edit-popup-choice-field"]' => array('value' => 1),
          ),
        ),
      );
      $elements['popup']['label'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Show field label'),
        '#default_value' => empty($popup_settings['label']) ? $default_settings['popup']['label'] : $popup_settings['label'],  
        '#states' => array(
          'visible' => array(
            ':input[id="edit-popup-choice-field"]' => array('value' => 1),
          ),
        ),
      );
      $elements['zoom'] = array(
        '#type' => 'fieldset',
        '#title' => $this->t('Zoom'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );
      $zoom_settings = $this->getSetting('zoom');
      $elements['zoom']['default'] = array(
        '#type' => 'select',
        '#options' => range(1, 23),
        '#title' => $this->t('Default zoom level'),
        '#default_value' => empty($zoom_settings['default']) ? $default_settings['zoom']['default'] : $zoom_settings['default'],  
        '#description' => $this->t('Should be between the Min and Max zoom level.'),
        '#required' => TRUE,
      );
      $elements['zoom']['max'] = array(
        '#type' => 'select',
        '#options' => range(1, 23),
        '#title' => $this->t('Max zoom level'),
        '#default_value' => empty($zoom_settings['max']) ? $default_settings['zoom']['max'] : $zoom_settings['max'],            
        '#description' => $this->t('Should be greater then the Min zoom level.'),
        '#required' => TRUE,
      );
      $elements['zoom']['min'] = array(
        '#type' => 'select',
        '#options' => range(1, 23),
        '#title' => $this->t('Min zoom level'),
        '#default_value' => empty($zoom_settings['min']) ? $default_settings['zoom']['min'] : $zoom_settings['min'],                      
        '#description' => $this->t('Should be smaller then the Max zoom level.'),
        '#required' => TRUE,
      );
      $elements['maptypecontrol'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Enable Map Type control'),
        '#default_value' => $this->getSetting('maptypecontrol'),
      );
      $elements['pancontrol'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Enable panning control'),
        '#default_value' => $this->getSetting('pancontrol'),
      );
      $elements['streetviewcontrol'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Enable street view control'),
        '#default_value' => $this->getSetting('streetviewcontrol'),
      );
      $elements['zoomcontrol'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Enable zoom control'),
        '#default_value' => $this->getSetting('zoomcontrol'),
      );
      $elements['scrollwheel'] = array(
        '#type' => 'checkbox',
        '#title' => $this->t('Enable scrollwheel'),
        '#default_value' => $this->getSetting('scrollwheel'),
      );
    //}

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = array();
    $summary[] = $this->t('Width: <b>%width</b><br />Height: <b>%height</b>',
      array('%width' => $this->getSetting('width'), '%height' => $this->getSetting('height')));
    $style_settings = $this->getSetting('style');
    if ($style_settings['style']) {
      $summary[] = $this->t('<br />Map style: <b>Custom</b>');
    }    
    if ($style_settings['pin']) {
      $summary[] = $this->t('<br />Pin style: <b>%pin</b>', array('%pin' => $style_settings['pin']));
    }
    $summary[] = $this->t('<br />Map type: <b>%maptype</b>', array('%maptype' => $style_settings['maptype']));
    if ($style_settings['pin']) {
      $summary[] = $this->t('<br />Pin location: <b>%pin</b>', array('%pin' => $style_settings['pin']));
    }
    $popup_settings =$this->getSetting('popup');
    if ($popup_settings['choice'] == 1) {
      $summary[] = $this->t('<br />Popup shows field <b>%field</b>', array('%field' => $popup_settings['text']));
      $readable = array(FALSE => $this->t('without'), TRUE => $this->t('with'));
      $summary[] = $this->t('<b>%label</b> label', array('%label' => $readable[$popup_settings['label']]));
    }
    if ($popup_settings['choice'] == 2) {
      $summary[] = $this->t('<br />Popup shows view mode <b>%viewmode</b>', array('%viewmode' => $popup_settings['view_mode']));
    }
    $zoom_settings = $this->getSetting('zoom');
    $summary[] = $this->t('<br />Default zoom: <b>%zoom</b>', array('%zoom' => $zoom_settings['default']));
    $summary[] = $this->t('<br />Maximum zoom: <b>%maxzoom</b>', array('%maxzoom' => $zoom_settings['max']));
    $summary[] = $this->t('<br />Minimum zoom: <b>%minzoom</b>', array('%minzoom' => $zoom_settings['min']));
    return $summary;
  }
  
  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items) {
    $elements = array();    
    foreach ($items as $delta => $item) {
      $elements[$delta] = array(        
        '#markup' => theme_styled_google_map(array('location' => $item, 'settings' => $this->getSettings()))
      );
    }
    return $elements;
  }
  
}
