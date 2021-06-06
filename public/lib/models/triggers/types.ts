import {
  TJsonApiBody,
  TJsonApiData,
  TJsonApiRelation,
  TJsonApiRelationships,
  TJsonApiRelationshipData,
} from 'jsona/lib/JsonaTypes'

import {
  ActionInterface,
  ActionEntityTypes,
  ActionCreateInterface,
} from '@/lib/models/actions/types'
import {
  NotificationInterface,
  NotificationEntityTypes,
  NotificationCreateInterface,
} from '@/lib/models/notifications/types'
import {
  ConditionInterface,
  ConditionEntityTypes,
  ConditionCreateInterface,
} from '@/lib/models/conditions/types'

// ENTITY TYPES
// ============

export enum TriggerEntityTypes {
  AUTOMATIC = 'triggers-module/trigger-automatic',
  MANUAL = 'triggers-module/trigger-manual',
}

// ENTITY INTERFACE
// ================

export interface TriggerInterface {
  id: string
  type: TriggerEntityTypes

  draft: boolean

  name: string
  comment: string | null
  enabled: boolean

  owner: string | null

  // Relations
  relationshipNames: string[]

  actions: ActionInterface[]
  notifications: NotificationInterface[]
  conditions: ConditionInterface[]

  // Entity transformers
  isEnabled: boolean
  icon: string
  description: string

  isAutomatic: boolean
  isManual: boolean
  isDate: boolean
  isTime: boolean
}

// API RESPONSES
// =============

interface TriggerAttributesResponseInterface {
  name: string
  comment: string | null
  enabled: boolean

  owner: string | null
}

interface TriggerConditionRelationshipResponseInterface extends TJsonApiRelationshipData {
  id: string
  type: ConditionEntityTypes
}

interface TriggerConditionsRelationshipsResponseInterface extends TJsonApiRelation {
  data: TriggerConditionRelationshipResponseInterface[]
}

interface TriggerNotificationRelationshipResponseInterface extends TJsonApiRelationshipData {
  id: string
  type: NotificationEntityTypes
}

interface TriggerNotificationsRelationshipsResponseInterface extends TJsonApiRelation {
  data: TriggerNotificationRelationshipResponseInterface[]
}

interface TriggerActionRelationshipResponseInterface extends TJsonApiRelationshipData {
  id: string
  type: ActionEntityTypes
}

interface TriggerActionsRelationshipsResponseInterface extends TJsonApiRelation {
  data: TriggerActionRelationshipResponseInterface[]
}

interface TriggerRelationshipsResponseInterface extends TJsonApiRelationships {
  actions: TriggerActionsRelationshipsResponseInterface
  notifications: TriggerNotificationsRelationshipsResponseInterface
}

interface AutomaticTriggerRelationshipsResponseInterface extends TriggerRelationshipsResponseInterface {
  conditions: TriggerConditionsRelationshipsResponseInterface
}

export interface TriggerDataResponseInterface extends TJsonApiData {
  id: string
  type: TriggerEntityTypes
  attributes: TriggerAttributesResponseInterface
  relationships: TriggerRelationshipsResponseInterface | AutomaticTriggerRelationshipsResponseInterface
}

export interface TriggerResponseInterface extends TJsonApiBody {
  data: TriggerDataResponseInterface
}

export interface TriggersResponseInterface extends TJsonApiBody {
  data: TriggerDataResponseInterface[]
}

// CREATE ENTITY INTERFACES
// ========================

export interface TriggerCreateInterface {
  type: TriggerEntityTypes

  name?: string
  comment?: string | null
  enabled?: boolean

  actions: ActionCreateInterface[]
  notifications: NotificationCreateInterface[]
}

export type CreateManualTriggerInterface = TriggerCreateInterface

export interface CreateAutomaticTriggerInterface extends TriggerCreateInterface {
  conditions: ConditionCreateInterface[]
}

// UPDATE ENTITY INTERFACES
// ========================

export interface TriggerUpdateInterface {
  name?: string
  comment?: string | null
  enabled?: boolean
}
