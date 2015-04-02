<?php
/**
 * ownCloud - Calendar App
 *
 * @author Georg Ehrke
 * @copyright 2014 Georg Ehrke <oc.list@georgehrke.com>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU AFFERO GENERAL PUBLIC LICENSE for more details.
 *
 * You should have received a copy of the GNU Affero General Public
 * License along with this library.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace OCA\Calendar\Db;

use OCA\Calendar\CorruptDataException;
use OCP\ILogger;

class ObjectCollectionFactory extends CollectionFactory {


	/**
	 * use if data is in ical format
	 */
	const FORMAT_ICAL = 3;


	/**
	 * use if data is in jcal format
	 */
	const FORMAT_JCAL = 4;


	/**
	 * @var ObjectFactory
	 */
	protected $entityFactory;


	/**
	 * @var ILogger
	 */
	protected $logger;


	/**
	 * @var \closure
	 */
	protected $iCal;


	/**
	 * @var \closure
	 */
	protected $jCal;


	/**
	 * @param ObjectFactory $entityFactory
	 * @param ILogger $logger
	 * @param \closure $iCal
	 * @param \closure $jCal
	 */
	public function __construct(ObjectFactory $entityFactory, ILogger $logger, \closure $iCal, \closure $jCal) {
		$this->entityFactory = $entityFactory;
		$this->logger = $logger;
		$this->iCal = $iCal;
		$this->jCal = $jCal;
	}


	/**
	 * @param Object[] $entities
	 * @return ObjectCollection
	 */
	public function createFromEntities(array $entities) {
		return ObjectCollection::fromArray($entities);
	}


	/**
	 * @param array $data
	 * @param integer $format
	 * @return ObjectCollection
	 */
	public function createFromData(array $data, $format) {
		$collection = new ObjectCollection();

		foreach($data as $item) {
			try {
				$entity = $this->entityFactory->createEntity($item, $format);
				$collection->add($entity);
			} catch(CorruptDataException $ex) {
				$this->logger->info($ex->getMessage());
				continue;
			}
		}

		return $collection;
	}
}

/**
 * 			$objectCollection = new ObjectCollection();

$splitter = new JCalendar($this->request->getParams());
while($vobject = $splitter->getNext()) {
if (!($vobject instanceof VCalendar)) {
continue;
}

SabreUtility::removeXOCAttrFromComponent($vobject);
$object = new Object();
$object->fromVObject($vobject);
$objectCollection->add($object);
}

if (count($objectCollection) === 1) {
$object = $objectCollection[0];
} else {
$object = $objectCollection;
}
 */

/*
$data = $this->getData();

//fix malformed timestamp in some google calendar events
//originally contributed by github.com/nezzi
$data = str_replace('CREATED:00001231T000000Z', 'CREATED:19700101T000000Z', $data);

//add some more fixes over time

$this->setData($data);*/