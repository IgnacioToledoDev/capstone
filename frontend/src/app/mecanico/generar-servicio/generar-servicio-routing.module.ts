import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { GenerarServicioPage } from './generar-servicio.page';

const routes: Routes = [
  {
    path: '',
    component: GenerarServicioPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class GenerarServicioPageRoutingModule {}
