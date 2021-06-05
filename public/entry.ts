// Import library
import { ModuleOrigin } from '@fastybird/modules-metadata'

import Trigger from '@/lib/triggers/Trigger'
import triggers from '@/lib/triggers'
import Condition from '@/lib/conditions/Condition'
import conditions from '@/lib/conditions'
import Action from '@/lib/actions/Action'
import actions from '@/lib/actions'
import Notification from '@/lib/notifications/Notification'
import notifications from '@/lib/notifications'

// Import typing
import { ComponentsInterface, GlobalConfigInterface, InstallFunction } from '@/types/triggers-module'

// install function executed by VuexORM.use()
const install: InstallFunction = function installVuexOrmWamp(components: ComponentsInterface, config: GlobalConfigInterface) {
  if (typeof config.originName !== 'undefined') {
    // @ts-ignore
    components.Model.prototype.$triggersModuleOrigin = config.originName
  } else {
    // @ts-ignore
    components.Model.prototype.$triggersModuleOrigin = ModuleOrigin.MODULE_DEVICES_ORIGIN
  }

  config.database.register(Trigger, triggers)
  config.database.register(Condition, conditions)
  config.database.register(Action, actions)
  config.database.register(Notification, notifications)
}

// Create module definition for VuexORM.use()
const plugin = {
  install,
}

// Default export is library as a whole, registered via VuexORM.use()
export default plugin

// Export model classes
export {
  Action,
  Condition,
  Notification,
  Trigger,
}

// Re-export plugin typing
export * from '@/types/triggers-module'
