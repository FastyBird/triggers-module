import { TriggerConditionOperator } from '@fastybird/modules-metadata'

import {
  TJsonApiBody,
  TJsonApiData,
  TJsonApiRelation,
  TJsonApiRelationships,
  TJsonApiRelationshipData,
} from 'jsona/lib/JsonaTypes'

import {
  TriggerInterface,
  TriggerEntityTypes,
} from '@/lib/triggers/types'

// ENTITY TYPES
// ============

export enum ConditionEntityTypes {
  TIME = 'triggers-module/condition-time',
  DATE = 'triggers-module/condition-date',
  DEVICE_PROPERTY = 'triggers-module/condition-device-property',
  CHANNEL_PROPERTY = 'triggers-module/condition-channel-property',
}

// ENTITY INTERFACE
// ================

export interface ConditionInterface {
  id: string
  type: ConditionEntityTypes

  draft: boolean

  enabled: boolean

  // Device & Channel property conditions specific
  operator?: TriggerConditionOperator
  operand?: string
  device?: string
  channel?: string
  property?: string

  // Time conditions specific
  time?: string
  days?: Array<number>

  // Date conditions specific
  date?: string

  // Relations
  relationshipNames: Array<string>

  triggerId: string

  trigger: TriggerInterface | null
  triggerBackward: TriggerInterface | null

  isDeviceProperty: boolean
  isChannelProperty: boolean
  isTime: boolean
  isDate: boolean
}

// API RESPONSES
// =============

interface ConditionAttributesResponseInterface {
  enabled: boolean

  // Device & Channel property conditions specific
  operator?: TriggerConditionOperator
  operand?: string
  device?: string
  channel?: string
  property?: string

  // Time conditions specific
  time?: string
  days?: Array<number>

  // Date conditions specific
  date?: string
}

interface ConditionTriggerRelationshipResponseInterface extends TJsonApiRelationshipData {
  id: string
  type: TriggerEntityTypes
}

interface ConditionTriggerRelationshipsResponseInterface extends TJsonApiRelation {
  data: ConditionTriggerRelationshipResponseInterface
}

interface ConditionRelationshipsResponseInterface extends TJsonApiRelationships {
  trigger: ConditionTriggerRelationshipsResponseInterface
}

export interface ConditionDataResponseInterface extends TJsonApiData {
  id: string
  type: ConditionEntityTypes
  attributes: ConditionAttributesResponseInterface
  relationships: ConditionRelationshipsResponseInterface
}

export interface ConditionResponseInterface extends TJsonApiBody {
  data: ConditionDataResponseInterface
}

export interface ConditionsResponseInterface extends TJsonApiBody {
  data: Array<ConditionDataResponseInterface>
}

// CREATE ENTITY INTERFACES
// ========================

export interface ConditionCreateInterface {
  type: ConditionEntityTypes

  enabled: boolean
}

export interface CreateDevicePropertyConditionInterface extends ConditionCreateInterface {
  operator: TriggerConditionOperator
  operand: string
  device: string
  property: string
}

export interface CreateChannelPropertyConditionInterface extends ConditionCreateInterface {
  operator: TriggerConditionOperator
  operand: string
  device: string
  channel: string
  property: string
}

export interface CreateDateConditionInterface extends ConditionCreateInterface {
  date: string
}

export interface CreateTimeConditionInterface extends ConditionCreateInterface {
  time: string
  days: Array<number>
}

// UPDATE ENTITY INTERFACES
// ========================

export interface ConditionUpdateInterface {
  enabled?: boolean
}

export interface UpdateDevicePropertyConditionInterface extends ConditionUpdateInterface {
  operator?: TriggerConditionOperator
  operand?: string
}

export interface UpdateChannelPropertyConditionInterface extends ConditionUpdateInterface {
  operator?: TriggerConditionOperator
  operand?: string
}

export interface UpdateDateConditionInterface extends ConditionUpdateInterface {
  date?: string
}

export interface UpdateTimeConditionInterface extends ConditionUpdateInterface {
  time?: string
  days?: Array<number>
}
