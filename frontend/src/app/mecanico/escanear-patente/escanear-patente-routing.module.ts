import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { EscanearPatentePage } from './escanear-patente.page';

const routes: Routes = [
  {
    path: '',
    component: EscanearPatentePage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class EscanearPatentePageRoutingModule {}
