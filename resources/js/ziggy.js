const Ziggy = {"url":"http:\/\/fastbooking.test","port":null,"defaults":{},"routes":{"login":{"uri":"login","methods":["GET","HEAD"]},"logout":{"uri":"logout","methods":["POST"]},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"]},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.update":{"uri":"reset-password","methods":["POST"]},"register":{"uri":"register","methods":["GET","HEAD"]},"user-profile-information.update":{"uri":"user\/profile-information","methods":["PUT"]},"user-password.update":{"uri":"user\/password","methods":["PUT"]},"password.confirm":{"uri":"user\/confirm-password","methods":["GET","HEAD"]},"password.confirmation":{"uri":"user\/confirmed-password-status","methods":["GET","HEAD"]},"two-factor.login":{"uri":"two-factor-challenge","methods":["GET","HEAD"]},"category.page":{"uri":"categories","methods":["GET","HEAD"]},"category.store":{"uri":"categories","methods":["POST"]},"category.update":{"uri":"categories\/{id}","methods":["PATCH"]},"category.delete":{"uri":"categories\/{id}","methods":["DELETE"]},"category.delete.all":{"uri":"selected-delete","methods":["POST"]},"tour.package.page":{"uri":"tour-package","methods":["GET","HEAD"]},"tour.package.store":{"uri":"tour-package","methods":["POST"]},"category.list.data":{"uri":"category-list","methods":["GET","HEAD"]},"tour.package.data":{"uri":"tour-package-list","methods":["GET","HEAD"]},"tour.package.update":{"uri":"tour-package-update","methods":["PUT"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    for (let name in window.Ziggy.routes) {
        Ziggy.routes[name] = window.Ziggy.routes[name];
    }
}

export { Ziggy };
