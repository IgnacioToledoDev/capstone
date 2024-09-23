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
  {
    path: 'Mecanico/register-user',
    loadChildren: () => import('./Mecanico/register-user/register-user.module').then( m => m.RegisterUserPageModule)
  },
  {
    path: 'Mecanico/agregar-vehiculo',
    loadChildren: () => import('./Mecanico/agregar-vehiculo/agregar-vehiculo.module').then( m => m.AgregarVehiculoPageModule)
  },




];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
