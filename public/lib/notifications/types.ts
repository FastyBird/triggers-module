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

export enum NotificationEntityTypes {
  EMAIL = 'triggers-module/notification-email',
  SMS = 'triggers-module/notification-sms',
}

// ENTITY INTERFACE
// ================

export interface NotificationInterface {
  id: string
  type: NotificationEntityTypes

  draft: boolean

  enabled: boolean

  // Email notifications specific
  email?: string

  // SMS notifications specific
  phone?: string

  // Relations
  relationshipNames: Array<string>

  triggerId: string

  trigger: TriggerInterface | null
  triggerBackward: TriggerInterface | null

  isSms: boolean
  isEmail: boolean
}

// API RESPONSES
// =============

interface NotificationAttributesResponseInterface {
  enabled: boolean

  // Email notifications specific
  email?: string

  // SMS notifications specific
  phone?: string
}

interface NotificationTriggerRelationshipResponseInterface extends TJsonApiRelationshipData {
  id: string
  type: TriggerEntityTypes
}

interface NotificationTriggerRelationshipsResponseInterface extends TJsonApiRelation {
  data: NotificationTriggerRelationshipResponseInterface
}

interface NotificationRelationshipsResponseInterface extends TJsonApiRelationships {
  trigger: NotificationTriggerRelationshipsResponseInterface
}

export interface NotificationDataResponseInterface extends TJsonApiData {
  id: string
  type: NotificationEntityTypes
  attributes: NotificationAttributesResponseInterface
  relationships: NotificationRelationshipsResponseInterface
}

export interface NotificationResponseInterface extends TJsonApiBody {
  data: NotificationDataResponseInterface
}

export interface NotificationsResponseInterface extends TJsonApiBody {
  data: Array<NotificationDataResponseInterface>
}

// CREATE ENTITY INTERFACES
// ========================

export interface NotificationCreateInterface {
  type: NotificationEntityTypes

  enabled: boolean
}

export interface CreateSmsNotificationInterface extends NotificationCreateInterface {
  phone: string
}

export interface CreateEmailNotificationInterface extends NotificationCreateInterface {
  email: string
}

// UPDATE ENTITY INTERFACES
// ========================

export interface NotificationUpdateInterface {
  enabled?: boolean
}

export interface UpdateSmsNotificationInterface extends NotificationUpdateInterface {
  phone?: string
}

export interface UpdateEmailNotificationInterface extends NotificationUpdateInterface {
  email?: string
}
