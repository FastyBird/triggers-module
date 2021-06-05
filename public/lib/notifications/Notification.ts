import {
  Fields,
  Item,
  Model,
} from '@vuex-orm/core'

import Trigger from '@/lib/triggers/Trigger'
import { TriggerInterface } from '@/lib/triggers/types'
import {
  CreateEmailNotificationInterface,
  CreateSmsNotificationInterface,
  NotificationEntityTypes,
  NotificationInterface,
  UpdateEmailNotificationInterface,
  UpdateSmsNotificationInterface,
} from '@/lib/notifications/types'

// ENTITY MODEL
// ============
export default class Notification extends Model implements NotificationInterface {
  static get entity(): string {
    return 'notification'
  }

  static fields(): Fields {
    return {
      id: this.string(''),
      type: this.string(''),

      draft: this.boolean(false),

      enabled: this.boolean(true),

      // Email notification
      email: this.string(null).nullable(),

      // SMS notification
      phone: this.string(null).nullable(),

      relationshipNames: this.attr([]),

      trigger: this.belongsTo(Trigger, 'id'),
      triggerBackward: this.hasOne(Trigger, 'id', 'triggerId'),

      triggerId: this.string(''),
    }
  }

  id!: string
  type!: NotificationEntityTypes

  draft!: boolean

  enabled!: boolean

  email!: string

  phone!: string

  relationshipNames!: Array<string>

  trigger!: TriggerInterface | null
  triggerBackward!: TriggerInterface | null

  triggerId!: string

  get isSms(): boolean {
    return this.type === NotificationEntityTypes.SMS
  }

  get isEmail(): boolean {
    return this.type === NotificationEntityTypes.EMAIL
  }

  static async get(trigger: TriggerInterface, id: string): Promise<boolean> {
    return await Notification.dispatch('get', {
      trigger,
      id,
    })
  }

  static async fetch(trigger: TriggerInterface): Promise<boolean> {
    return await Notification.dispatch('fetch', {
      trigger,
    })
  }

  static async add(trigger: TriggerInterface, data: CreateSmsNotificationInterface | CreateEmailNotificationInterface, id?: string | null, draft = true): Promise<Item<Notification>> {
    return await Notification.dispatch('add', {
      trigger,
      id,
      draft,
      data,
    })
  }

  static async edit(notification: NotificationInterface, data: UpdateSmsNotificationInterface | UpdateEmailNotificationInterface): Promise<Item<Notification>> {
    return await Notification.dispatch('edit', {
      notification,
      data,
    })
  }

  static async save(notification: NotificationInterface): Promise<Item<Notification>> {
    return await Notification.dispatch('save', {
      notification,
    })
  }

  static async remove(notification: NotificationInterface): Promise<boolean> {
    return await Notification.dispatch('remove', {
      notification,
    })
  }

  static reset(): void {
    Notification.dispatch('reset')
  }
}
