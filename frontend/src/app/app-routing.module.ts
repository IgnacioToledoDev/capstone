import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { TerminosCondicionesComponent } from 'src/app/components/terminos-condiciones/terminos-condiciones.component'
import { MenuComponent } from './components/menu/menu.component';
const routes: Routes = [
  {
    path: 'components/terminos-y-condiciones',
    component: TerminosCondicionesComponent
  },
  {
    path: 'components/menu',
    component: MenuComponent
  },
  {
    path: '',
    redirectTo: 'bienvenidos',
    pathMatch: 'full'
  },
  {
    path: 'home',
    loadChildren: () => import('./home/home.module').then( m => m.HomePageModule)
  },

  {
    path: 'bienvenidos',
    loadChildren: () => import('./bienvenidos/bienvenidos.module').then( m => m.BienvenidosPageModule)
  },
  {
    path: 'inicio-sesion',
    loadChildren: () => import('./inicio-sesion/inicio-sesion.module').then( m => m.InicioSesionPageModule)
  },
  {
    path: 'recuperar-contrasena',
    loadChildren: () => import('./recuperar-contrasena/recuperar-contrasena.module').then( m => m.RecuperarContrasenaPageModule)
  },
  {
    path: 'nueva-contrasena',
    loadChildren: () => import('./nueva-contrasena/nueva-contrasena.module').then( m => m.NuevaContrasenaPageModule)
  },
  {
    path: 'Mecanico/home-mecanico',
    loadChildren: () => import('./Mecanico/home-mecanico/home-mecanico.module').then( m => m.HomeMecanicoPageModule)
  },
<<<<<<< HEAD
<<<<<<< HEAD


=======
=======
>>>>>>> develop
  {
    path: 'mecanico/register-user',
    loadChildren: () => import('./mecanico/register-user/register-user.module').then( m => m.RegisterUserPageModule)
  },
  {
    path: 'mecanico/agregar-vehiculo',
    loadChildren: () => import('./mecanico/agregar-vehiculo/agregar-vehiculo.module').then( m => m.AgregarVehiculoPageModule)
  },
  {
    path: 'mecanico/generar-servicio',
    loadChildren: () => import('./mecanico/generar-servicio/generar-servicio.module').then( m => m.GenerarServicioPageModule)
  },
  {
    path: 'mecanico/cotizar',
    loadChildren: () => import('./mecanico/cotizar/cotizar.module').then( m => m.CotizarPageModule)
  },
  {
    path: 'mecanico/historial',
    loadChildren: () => import('./mecanico/historial/historial.module').then( m => m.HistorialPageModule)
  },
  {
    path: 'mecanico/info-ser-cli',
    loadChildren: () => import('./mecanico/info-ser-cli/info-ser-cli.module').then( m => m.InfoSerCliPageModule)
  },
<<<<<<< HEAD
>>>>>>> develop
=======
=======


>>>>>>> 92567a3b (Revert "Cap 87")
>>>>>>> develop
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
