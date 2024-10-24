import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CalificarPage } from './calificar.page';

const routes: Routes = [
  {
    path: '',
    component: CalificarPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CalificarPageRoutingModule {}
