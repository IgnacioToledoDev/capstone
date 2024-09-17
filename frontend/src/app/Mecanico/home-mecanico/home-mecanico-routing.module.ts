import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { HomeMecanicoPage } from './home-mecanico.page';

const routes: Routes = [
  {
    path: '',
    component: HomeMecanicoPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class HomeMecanicoPageRoutingModule {}
