Yii2Metronic
======================
Yii2Metronic is a collection of Yii2 components(widgets) based on responsive and multipurpose admin theme
called Metronic (v2.0). Powered with Twitter Bootstrap 3.1.0 Framework. 

Metronic can be used for any type of web applications: custom admin panels, admin dashboards, CMS, CRM, SAAS and websites: business, corporate, portfolio, blog.
Metronic has a sleek, clean and intuitive metro style design which makes your next project look awesome and yet user
friendly. Metronic has a huge collection of plugins and UI components and works seamlessly on all major web browsers,
tablets and phones. 

More than 30 widgets for Yii2 Metronic.

Basic configuration:
```
    'components' => [ 
        'metronic' => [
            'class' => 'icron\metronic\Metronic',
            'color' => 'default',
            'layoutOption' => \icron\metronic\Metronic::LAYOUT_FLUID,
            'headerOption' => 'fixed',
        ],
    ],
    'preload' => ['metronic'],
```

Some examples:
#### Menu
Metronic menu displays a multi-level menu using nested HTML lists.

 The main property of Menu is [[items]], which specifies the possible items in the menu.
  A menu item can contain sub-items which specify the sub-menu under that menu item.
  Menu checks the current route and request parameters to toggle certain menu items
  with active state.
  Note that Menu only renders the HTML tags about the menu. It does do any styling.
  You are responsible to provide CSS styles to make it look like a real menu.
 
  The following example shows how to use Menu:
 
  ```php
  echo Menu::widget([
      'items' => [
          // Important: you need to specify url as 'controller/action',
          // not just as 'controller' even if default action is used.
          [
            'icon' => '',
            'label' => 'Home',
            'url' => ['site/index']
          ],
          // 'Products' menu item will be selected as long as the route is 'product/index'
          ['label' => 'Products', 'url' => ['product/index'], 'items' => [
              ['label' => 'New Arrivals', 'url' => ['product/index', 'tag' => 'new']],
              ['label' => 'Most Popular', 'url' => ['product/index', 'tag' => 'popular']],
          ]],
          ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
      ],
      'search' => [
          // required, whether search box is visible. Defaults to 'true'.
          'visible' => true,
          // optional, the configuration array for [[ActiveForm]].
          'form' => [],
          // optional, input options with default values
          'input' => [
              'name' => 'search',
              'value' => '',
              'options' => [
              'placeholder' => 'Search...',
          ]
      ],
  ]
  ]);
  ```
   #### Horizontal Menu
Horizontal Menu displays a multi-level menu using nested HTML lists.
 
  The main property of Menu is [[items]], which specifies the possible items in the menu.
  A menu item can contain sub-items which specify the sub-menu under that menu item.
 Menu checks the current route and request parameters to toggle certain menu items
  with active state.
  Note that Menu only renders the HTML tags about the menu. It does do any styling.
  You are responsible to provide CSS styles to make it look like a real menu.
 Supports multiple operating modes: classic, mega, and full mega(see [[HorizontalMenu::type]]).
 
  The following example shows how to use Menu:
 
  ```php
  // Classic menu with search box
  echo HorizontalMenu::widget([
      'items' => [
          // Important: you need to specify url as 'controller/action',
          // not just as 'controller' even if default action is used.
          ['label' => 'Home', 'url' => ['site/index']],
          // 'Products' menu item will be selected as long as the route is 'product/index'
          ['label' => 'Products', 'url' => ['product/index'], 'items' => [
              ['label' => 'New Arrivals', 'url' => ['product/index', 'tag' => 'new']],
              ['label' => 'Most Popular', 'url' => ['product/index', 'tag' => 'popular']],
          ]],
          ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
      ],
      'search' => [
          // required, whether search box is visible. Defaults to 'true'.
          'visible' => true,
          // optional, the configuration array for [[ActiveForm]].
          'form' => [],
          // optional, input options with default values
          'input' => [
              'name' => 'search',
              'value' => '',
              'options' => [
              'placeholder' => 'Search...',
          ]
      ],
  ]);
 
  // Mega Menu without search box
  echo HorizontalMenu::widget([
      'items' => [
          ['label' => 'Home', 'url' => ['site/index']],
          [
              'label' => 'Mega Menu',
              'type' => HorizontalMenu::ITEM_TYPE_FULL_MEGA,
               //optional, HTML text for last column
              'text' => 'Other HTML text',
              'items' => [
                  [
                      'label' => 'Column 1', // First column title
                      'items' => [
                          ['label' => 'Column 1 Item 1'],
                          ['label' => 'Column 1 Item 2'],
                      ]
                  ],
                  [
                      'label' => 'Column 2', // Second column title
                      'items' => [
                          ['label' => 'Column 2 Item 1'],
                          ['label' => 'Column 2 Item 2'],
                      ]
                  ],
              ]
          ],
          ['label' => 'Login', 'url' => ['site/login'], 'visible' => Yii::$app->user->isGuest],
      ],
  ]);
  ```
 #### Nav
  
Nav renders a nav HTML component.
 For example:
 
  ```php
  echo Nav::widget([
      'items' => [
          [
              'icon' => 'fa fa-warning',
              'badge' => Badge::widget(['label' => 'New', 'round' => false]),
              'label' => 'Home',
              'url' => ['site/index'],
              'linkOptions' => [...],
          ],
          [
              'label' => 'Dropdown',
              'items' => [
                   ['label' => 'Level 1 - Dropdown A', 'url' => '#'],
                   '<li class="divider"></li>',
                   '<li class="dropdown-header">Dropdown Header</li>',
                   ['label' => 'Level 1 - Dropdown B', 'url' => '#'],
              ],
          ],
      ],
  ]);
  ```
 
  **Note**: *Multilevel dropdowns beyond Level 1 are not supported in Bootstrap 3.*
  
 #### DateRangePicker
DateRangePicker renders dateRangePicker widget.
 
   There are two modes of operation of the widget are 'input' and 'advance'.
   Mode 'input' renders input HTML element and mode 'advance' renders span HTML element.
   Widget renders a hidden field with the model name that this widget is associated with
   and the current value of the selected date.
 
   For example, if [[model]] and [[attribute]] are not set:
  ```php
   DateRangePicker::widget([
       'mode' => DateRangePicker::MODE_ADVANCE,
       'labelDateFormat' => 'MMMM D, YYYY',
       'type' => DateRangePicker::TYPE_BLUE,
       'clientOptions' => [
           'format' => 'YYYY-MM-DD',
           'ranges' => new \yii\web\JsExpression("{
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
               'Last 7 Days': [moment().subtract('days', 6), moment()],
               'Last 30 Days': [moment().subtract('days', 29), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
           }"),
       ],
       'name' => 'date',
       'icon' => 'fa fa-calendar',
       'value' => '2014-02-15 - 2014-02-18',
  ]);
  ```
  #### Portlet
  
Portlet renders a metronic portlet.
  Any content enclosed between the [[begin()]] and [[end()]] calls of Portlet
  is treated as the content of the portlet.
  For example,
 
  ```php
  // Simple portlet
  Portlet::begin([
    'icon' => 'fa fa-bell-o',
    'title' => 'Title Portlet',
  ]);
  echo 'Body portlet';
  Portlet::end();
 
  // Portlet with tools, actions, scroller, events and remote content
  Portlet::begin([
    'title' => 'Extended Portlet',
    'scroller' => [
      'height' => 150,
      'footer' => ['label' => 'Show all', 'url' => '#'],
    ],
    'clientOptions' => [
      'loadSuccess' => new \yii\web\JsExpression('function(){ console.log("load success"); }'),
      'remote' => '/?r=site/about',
    ],
    'clientEvents' => [
      'close.mr.portlet' => 'function(e) { console.log("portlet closed"); e.preventDefault(); }'
    ],
    'tools' => [
      Portlet::TOOL_RELOAD,
      Portlet::TOOL_MINIMIZE,
      Portlet::TOOL_CLOSE,
    ],
  ]);
  ```
   #### Select2
   
  Select2 renders Select2 component.
  For example:
  ```php
  echo Select2::widget([
      'name' => 'select',
      'data' => ['1' => 'Item 1', '2' => 'Item 2'],
      'multiple' => true,
  ]);
  ```
 
 #### Badge
     
Badge widget. For example,
```
  Badge::widget([
      'label' => 'NEW',
      'type' => Badge::TYPE_SUCCESS,
      'round'
  ]);
 ```
  #### IonRangeSlider
IonRangeSlider renders ionRangeSlider widget.
  For example, if [[model]] and [[attribute]] are not set:
  ```php
  echo IonRangeSlider::widget([
      'name' => 'ionRangeSlider',
      'clientOptions' => [
          'min' => 0,
          'max' => 5000,
          'from' => 1000, // default value
          'to' => 4000, // default value
          'type' => 'double',
          'step' => 1,
          'prefix' => "$",
          'prettify' => false,
          'hasGrid' => true
      ],
  ]);
  ```
  #### Spinner 
  
Spinner renders an spinner Fuel UX widget.
 For example:
 
  ```php
  echo Spinner::widget([
      'model' => $model,
      'attribute' => 'country',
      'size' => Spinner::SIZE_SMALL,
      'buttonsLocation' => Spinner::BUTTONS_LOCATION_VERTICAL,
      'clientOptions' => ['step' => 2],
      'clientEvents' => ['changed' => 'function(event, value){ console.log(value);}'],
  ]);
  ```
 
  The following example will use the name property instead:
 
  ```php
  echo Spinner::widget([
      'name'  => 'country',
      'clientOptions' => ['step' => 2],
  ]);
 ```
  #### Accordion 
   Accordion renders an accordion Metronic component.
 For example:
 
  ```php
  echo Accordion::widget([
      'items' => [
          [
              'header' => 'Item 1',
              'content' => 'Content 1...',
              // open its content by default
              'contentOptions' => ['class' => 'in'],
              'type' => Accordion::ITEM_TYPE_SUCCESS,
          ],
          [
              'header' => 'Item 2',
              'content' => 'Content 2...',
          ],
      ],
      'itemConfig' => ['showIcon' => true],
 ]);
  ```
  #### Note
  
Note renders a metronic button.
  For example,
  ```php
  Note::widget([
      'title' => 'Success! Some Header Goes Here',
      'body' => 'Duis mollis, est non commodo luctus',
      'type' => Note::TYPE_INFO,
  ]);
  ```
 
  The following example will show the content enclosed between the [[begin()]]
  and [[end()]] calls within the alert box:
  ```php
  Note::begin(['type' => Note::TYPE_DANGER]);
  echo 'Some title and body';
  Note::end();
  ```
  
  ##### See more widgets in code documentation ...
  