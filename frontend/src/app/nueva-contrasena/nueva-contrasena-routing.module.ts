import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { NuevaContrasenaPage } from './nueva-contrasena.page';

const routes: Routes = [
  {
    path: '',
    component: NuevaContrasenaPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class NuevaContrasenaPageRoutingModule {}
