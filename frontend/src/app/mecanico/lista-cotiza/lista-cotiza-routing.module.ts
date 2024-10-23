import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ListaCotizaPage } from './lista-cotiza.page';

const routes: Routes = [
  {
    path: '',
    component: ListaCotizaPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ListaCotizaPageRoutingModule {}
