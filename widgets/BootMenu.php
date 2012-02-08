<?php
/**
 * BootMenu class file.
 * @author Christoffer Niska <ChristofferNiska@gmail.com>
 * @copyright Copyright &copy; Christoffer Niska 2011-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */

Yii::import('bootstrap.widgets.BootWidget');

/**
 * Bootstrap menu widget.
 * @since 0.9.8
 */
class BootMenu extends BootWidget
{
	// The different menu types.
	const TYPE_UNSTYLED = '';
	const TYPE_TABS = 'tabs';
	const TYPE_PILLS = 'pills';
	const TYPE_LIST = 'list';

	/**
	 * @var string the menu type.
	 * Valid values are '', 'tabs' and 'pills'. Defaults to ''.
	 */
	public $type = self::TYPE_UNSTYLED;
	/**
	 * @var boolean whether to stack navigation items.
	 */
	public $stacked = false;
	/**
	 * @var boolean whether to enable the scroll-spy.
	 */
	public $scrollspy = false;
	/**
	 * @var array the menu items.
	 */
	public $items = array();
	/**
	 * @var string the item template.
	 */
	public $itemTemplate;
	/**
	 * @var boolean whether to encode item labels.
	 */
	public $encodeLabel = true;
	/**
	 * @var array the HTML options for dropdown menus.
	 */
	public $dropdownOptions = array();

	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		$this->htmlOptions['id'] = $this->getId();
		$route = $this->controller->getRoute();
		$this->items = $this->normalizeItems($this->items, $route);

		if ($this->scrollspy)
			Yii::app()->clientScript->registerScript(__CLASS__.'#'.$this->id, "jQuery('#{$this->id}').scrollspy();");
	}

	/**
	 * Runs the widget.
	 */
	public function run()
	{
		if (!empty($this->items))
		{
			$cssClass = 'nav';

			if (!empty($this->type))
				$cssClass .= ' nav-'.$this->type;

			if ($this->type !== self::TYPE_LIST && $this->stacked)
				$cssClass .= ' nav-stacked';

			if (isset($this->htmlOptions['class']))
				$this->htmlOptions['class'] .= ' '.$cssClass;
			else
				$this->htmlOptions['class'] = $cssClass;

			echo CHtml::openTag('ul', $this->htmlOptions).PHP_EOL;
			$this->renderItems($this->items);
			echo '</ul>';
		}
	}

	/**
	 * Renders the items in this menu.
	 * @param array $items the menu items
	 */
	protected function renderItems($items)
	{
		foreach ($items as $item)
		{
			if (!is_array($item))
				echo '<li class="divider"></li>';
			else
			{
				$options = isset($item['itemOptions']) ? $item['itemOptions'] : array();
				$class = array();

				if (isset($item['items']))
					$class[] = 'dropdown';

				if ($item['active'])
					$class[] = 'active';

				if($class !== array())
				{
					if(empty($options['class']))
						$options['class'] = implode(' ',$class);
					else
						$options['class'] .= ' '.implode(' ',$class);
				}

				echo CHtml::openTag('li', $options);

				$menu = $this->renderItem($item);

				if (isset($this->itemTemplate) || isset($item['template']))
				{
					$template = isset($item['template']) ? $item['template'] : $this->itemTemplate;
					echo strtr($template, array('{menu}'=>$menu));
				}
				else
					echo $menu;

				if(isset($item['items']) && !empty($item['items']))
				{
					if (isset($item['dropdownOptions']['class']))
						$item['dropdownOptions']['class'] .= ' dropdown-menu';
					else
						$item['dropdownOptions']['class'] = 'dropdown-menu';

					$dropdownOptions = isset($item['dropdownOptions'])
							? $item['dropdownOptions'] : $this->dropdownOptions;

					echo CHtml::openTag('ul', $dropdownOptions).PHP_EOL;
					$this->renderItems($item['items']);
					echo '</ul>'.PHP_EOL;
				}

				echo '</li>'.PHP_EOL;
			}
		}
	}

	/**
	 * Renders a single item in this menu.
	 * @param array $item the item configuration
	 * @return string the rendered item
	 */
	protected function renderItem($item)
	{
		if (isset($item['items']))
		{
			if (!isset($item['url']))
				$item['url'] = '#';

			if (isset($item['linkOptions']['class']))
				$item['linkOptions']['class'] .= ' dropdown-toggle';
			else
				$item['linkOptions']['class'] = 'dropdown-toggle';

			$item['label'] .= ' <b class="caret"></b>';
			$item['linkOptions']['data-toggle'] = 'dropdown';
		}

		if (isset($item['url']))
			return CHtml::link($item['label'], $item['url'], isset($item['linkOptions']) ? $item['linkOptions'] : array());
		else
			return $item['label'];
	}

	/**
	 * Normalizes the items in this menu.
	 * @param array $items the items to be normalized
	 * @param string $route the route of the current request
	 * @return array the normalized menu items
	 */
	protected function normalizeItems($items, $route)
	{
		foreach ($items as $i => $item)
		{
			if (isset($item['visible']) && !$item['visible'])
			{
				unset($items[$i]);
				continue;
			}

			if (!isset($item['label']))
				$item['label'] = '';

			if (isset($item['encodeLabel']) && $item['encodeLabel'])
				$items[$i]['label'] = CHtml::encode($item['label']);

			if (($this->encodeLabel && !isset($item['encodeLabel']))
					|| (isset($item['encodeLabel']) && $item['encodeLabel'] !== false))
				$items[$i]['label'] = CHtml::encode($item['label']);

			if (!empty($item['items']) && is_array($item['items']))
			{
				$items[$i]['items'] = $this->normalizeItems($item['items'], $route);

				if (empty($items[$i]['items']))
					unset($items[$i]['items']);
			}

			if (!isset($item['active']))
				$items[$i]['active'] = $this->isItemActive($item, $route);
		}

		return array_values($items);
	}

	/**
	 * Checks whether a menu item is active.
	 * @param array $item the menu item to be checked
	 * @param string $route the route of the current request
	 * @return boolean whether the menu item is active
	 */
	protected function isItemActive($item,$route)
	{
		if (isset($item['url']) && is_array($item['url']) && !strcasecmp(trim($item['url'][0], '/'), $route))
		{
			if (count($item['url']) > 1)
			{
				foreach (array_splice($item['url'], 1) as $name=>$value)
				{
					if (!isset($_GET[$name]) || $_GET[$name] != $value)
						return false;
				}
			}

			return true;
		}

		return false;
	}
}
