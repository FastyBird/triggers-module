#     Copyright 2021. FastyBird s.r.o.
#
#     Licensed under the Apache License, Version 2.0 (the "License");
#     you may not use this file except in compliance with the License.
#     You may obtain a copy of the License at
#
#         http://www.apache.org/licenses/LICENSE-2.0
#
#     Unless required by applicable law or agreed to in writing, software
#     distributed under the License is distributed on an "AS IS" BASIS,
#     WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
#     See the License for the specific language governing permissions and
#     limitations under the License.

# Test dependencies
import uuid

# Library libs
from triggers_module.items import TriggerControlItem
from triggers_module.repositories import trigger_control_repository
from modules_metadata.routing import RoutingKey

# Tests libs
from tests.pytests.tests import DbTestCase


class TestTriggersControlsRepository(DbTestCase):
    def test_repository_iterator(self) -> None:
        trigger_control_repository.initialize()

        self.assertEqual(3, len(trigger_control_repository))

    # -----------------------------------------------------------------------------

    def test_get_item(self) -> None:
        trigger_control_repository.initialize()

        control_item = trigger_control_repository.get_by_id(
            uuid.UUID("177d6fc7-1905-4fd9-b847-e2da8189dd6a", version=4)
        )

        self.assertIsInstance(control_item, TriggerControlItem)
        self.assertEqual("trigger", control_item.name)

    # -----------------------------------------------------------------------------

    def test_create_from_exchange(self) -> None:
        trigger_control_repository.initialize()

        result: bool = trigger_control_repository.create_from_exchange(
            RoutingKey(RoutingKey.TRIGGERS_CONTROL_ENTITY_CREATED),
            {
                "id": "177d6fc7-1905-4fd9-b847-e2da8189dd6a",
                "name": "trigger",
                "trigger": "c64ba1c4-0eda-4cab-87a0-4d634f7b67f4",
            },
        )

        self.assertTrue(result)

        control_item = trigger_control_repository.get_by_id(
            uuid.UUID("177d6fc7-1905-4fd9-b847-e2da8189dd6a", version=4)
        )

        self.assertIsInstance(control_item, TriggerControlItem)
        self.assertEqual("177d6fc7-1905-4fd9-b847-e2da8189dd6a", control_item.control_id.__str__())
        self.assertEqual({
            "id": "177d6fc7-1905-4fd9-b847-e2da8189dd6a",
            "name": "trigger",
            "trigger": "c64ba1c4-0eda-4cab-87a0-4d634f7b67f4",
        }, control_item.to_dict())

    # -----------------------------------------------------------------------------

    def test_update_from_exchange(self) -> None:
        trigger_control_repository.initialize()

        result: bool = trigger_control_repository.update_from_exchange(
            RoutingKey(RoutingKey.TRIGGERS_CONTROL_ENTITY_UPDATED),
            {
                "id": "177d6fc7-1905-4fd9-b847-e2da8189dd6a",
                "name": "not edited name",
                "trigger": "c64ba1c4-0eda-4cab-87a0-4d634f7b67f4",
            },
        )

        self.assertTrue(result)

        control_item = trigger_control_repository.get_by_id(
            uuid.UUID("177d6fc7-1905-4fd9-b847-e2da8189dd6a", version=4)
        )

        self.assertIsInstance(control_item, TriggerControlItem)
        self.assertEqual("177d6fc7-1905-4fd9-b847-e2da8189dd6a", control_item.control_id.__str__())
        self.assertEqual({
            "id": "177d6fc7-1905-4fd9-b847-e2da8189dd6a",
            "name": "trigger",
            "trigger": "c64ba1c4-0eda-4cab-87a0-4d634f7b67f4",
        }, control_item.to_dict())

    # -----------------------------------------------------------------------------

    def test_delete_from_exchange(self) -> None:
        trigger_control_repository.initialize()

        control_item = trigger_control_repository.get_by_id(
            uuid.UUID("177d6fc7-1905-4fd9-b847-e2da8189dd6a", version=4)
        )

        self.assertIsInstance(control_item, TriggerControlItem)
        self.assertEqual("177d6fc7-1905-4fd9-b847-e2da8189dd6a", control_item.control_id.__str__())

        result: bool = trigger_control_repository.delete_from_exchange(
            RoutingKey(RoutingKey.TRIGGERS_CONTROL_ENTITY_DELETED),
            {
                "id": "177d6fc7-1905-4fd9-b847-e2da8189dd6a",
                "name": "trigger",
                "trigger": "c64ba1c4-0eda-4cab-87a0-4d634f7b67f4",
            },
        )

        self.assertTrue(result)

        control_item = trigger_control_repository.get_by_id(
            uuid.UUID("177d6fc7-1905-4fd9-b847-e2da8189dd6a", version=4)
        )

        self.assertIsNone(control_item)
