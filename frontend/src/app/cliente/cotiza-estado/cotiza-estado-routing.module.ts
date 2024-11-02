import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CotizaEstadoPage } from './cotiza-estado.page';

const routes: Routes = [
  {
    path: '',
    component: CotizaEstadoPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CotizaEstadoPageRoutingModule {}
