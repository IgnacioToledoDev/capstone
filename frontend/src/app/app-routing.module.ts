import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { TerminosCondicionesComponent } from './terminos-condiciones/terminos-condiciones.component';

const routes: Routes = [
  {
    path: 'terminos-y-condiciones',
    component: TerminosCondicionesComponent
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
  },  {
    path: 'recuperar-contrasena',
    loadChildren: () => import('./recuperar-contrasena/recuperar-contrasena.module').then( m => m.RecuperarContrasenaPageModule)
  },


];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
