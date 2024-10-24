import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ListaCarPage } from './lista-car.page';

const routes: Routes = [
  {
    path: '',
    component: ListaCarPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class ListaCarPageRoutingModule {}
