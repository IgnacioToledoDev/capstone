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
    path: 'mecanico/home-mecanico',
    loadChildren: () => import('./mecanico/home-mecanico/home-mecanico.module').then( m => m.HomeMecanicoPageModule)
  },
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
  {
    path: 'mecanico/seguimiento',
    loadChildren: () => import('./mecanico/seguimiento/seguimiento.module').then( m => m.SeguimientoPageModule)
  },
  {
    path: 'mecanico/escanear-patente',
    loadChildren: () => import('./mecanico/escanear-patente/escanear-patente.module').then( m => m.EscanearPatentePageModule)
  },
  {
    path: 'mecanico/escanear-qr',
    loadChildren: () => import('./mecanico/escanear-qr/escanear-qr.module').then( m => m.EscanearQrPageModule)
  },
  {
    path: 'mecanico/ajustes',
    loadChildren: () => import('./mecanico/ajustes/ajustes.module').then( m => m.AjustesPageModule)
  },
  {
    path: 'cliente/home-cliente',
    loadChildren: () => import('./cliente/home-cliente/home-cliente.module').then( m => m.HomeClientePageModule)
  },
  {
    path: 'cliente/info-mante',
    loadChildren: () => import('./cliente/info-mante/info-mante.module').then( m => m.InfoMantePageModule)
  },








];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
